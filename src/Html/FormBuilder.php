<?php

namespace NZTim\Html;

use DateTime;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class FormBuilder
{
    private UrlGenerator $url;
    private Factory $view;
    private bool $considerRequest = false;
    private Session $session;
    private mixed $model;
    private array $labels = [];
    private Request $request;
    private array $skipValueTypes = ['file', 'password', 'checkbox', 'radio'];
    private string|null $type = null;

    public function __construct(UrlGenerator $url, Factory $view, Session $session, Request $request = null)
    {
        $this->url = $url;
        $this->view = $view;
        $this->session = $session;
        $this->request = $request;
    }

    public function open(array $options = []): HtmlString
    {
        $method = strtolower(Arr::get($options, 'method', 'post'));
        $attributes['method'] = ($method === 'post') ? $method : 'get';
        $attributes['action'] = $this->getAction($options);
        $attributes['accept-charset'] = 'UTF-8';
        $append = $attributes['method'] === 'get' ? '' : csrf_field();
        $files = Arr::get($options, 'files', false);
        if ($files) {
            $options['enctype'] = 'multipart/form-data';
        }
        $attributes = array_merge($attributes, Arr::except($options, ['method', 'url', 'route', 'action', 'files']));
        $attributes = $this->attributesToHtml($attributes);
        return $this->toHtmlString('<form' . $attributes . '>' . $append);
    }

    public function model(mixed $model, array $options = []): HtmlString
    {
        $this->model = $model;

        return $this->open($options);
    }

    public function close(): HtmlString
    {
        $this->labels = [];
        $this->model = null;
        return $this->toHtmlString('</form>');
    }

    public function label(string $name, string $value = null, array $options = [], bool $escape_html = true): HtmlString
    {
        $this->labels[] = $name;
        $options = $this->attributesToHtml($options);
        $value = $this->formatLabel($name, $value);
        if ($escape_html) {
            $value = $this->entities($value);
        }
        return $this->toHtmlString('<label for="' . $name . '"' . $options . '>' . $value . '</label>');
    }

    protected function formatLabel(string $name, string|null $value): string
    {
        return $value ?: ucwords(str_replace('_', ' ', $name));
    }

    public function input(string $type, string $name, string $value = null, array $options = []): HtmlString
    {
        $this->type = $type;

        if (! isset($options['name'])) {
            $options['name'] = $name;
        }

        // We will get the appropriate value for the given field. We will look for the
        // value in the session for the value in the old input data then we'll look
        // in the model instance if one is set. Otherwise we will just use empty.
        $id = $this->getIdAttribute($name, $options);

        if (! in_array($type, $this->skipValueTypes)) {
            $value = $this->getValueAttribute($name, $value);
        }

        // Once we have the type, value, and ID we can merge them into the rest of the
        // attributes array so we can convert them into their HTML attribute format
        // when creating the HTML element. Then, we will return the entire input.
        $merge = compact('type', 'value', 'id');

        $options = array_merge($options, $merge);

        return $this->toHtmlString('<input' . $this->attributesToHtml($options) . '>');
    }

    /**
     * Create a text input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function text($name, $value = null, $options = [])
    {
        return $this->input('text', $name, $value, $options);
    }

    /**
     * Create a password input field.
     *
     * @param  string $name
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function password($name, $options = [])
    {
        return $this->input('password', $name, '', $options);
    }

    /**
     * Create a range input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function range($name, $value = null, $options = [])
    {
        return $this->input('range', $name, $value, $options);
    }

    /**
     * Create a hidden input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function hidden($name, $value = null, $options = [])
    {
        return $this->input('hidden', $name, $value, $options);
    }

    /**
     * Create a search input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function search($name, $value = null, $options = [])
    {
        return $this->input('search', $name, $value, $options);
    }

    /**
     * Create an e-mail input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function email($name, $value = null, $options = [])
    {
        return $this->input('email', $name, $value, $options);
    }

    /**
     * Create a tel input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function tel($name, $value = null, $options = [])
    {
        return $this->input('tel', $name, $value, $options);
    }

    /**
     * Create a number input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function number($name, $value = null, $options = [])
    {
        return $this->input('number', $name, $value, $options);
    }

    /**
     * Create a date input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function date($name, $value = null, $options = [])
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d');
        }

        return $this->input('date', $name, $value, $options);
    }

    /**
     * Create a datetime input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function datetime($name, $value = null, $options = [])
    {
        if ($value instanceof DateTime) {
            $value = $value->format(DateTime::RFC3339);
        }

        return $this->input('datetime', $name, $value, $options);
    }

    /**
     * Create a datetime-local input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function datetimeLocal($name, $value = null, $options = [])
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d\TH:i');
        }

        return $this->input('datetime-local', $name, $value, $options);
    }

    /**
     * Create a time input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function time($name, $value = null, $options = [])
    {
        if ($value instanceof DateTime) {
            $value = $value->format('H:i');
        }

        return $this->input('time', $name, $value, $options);
    }

    /**
     * Create a url input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function url($name, $value = null, $options = [])
    {
        return $this->input('url', $name, $value, $options);
    }

    /**
     * Create a week input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function week($name, $value = null, $options = [])
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-\WW');
        }

        return $this->input('week', $name, $value, $options);
    }

    /**
     * Create a file input field.
     *
     * @param  string $name
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function file($name, $options = [])
    {
        return $this->input('file', $name, null, $options);
    }

    /**
     * Create a textarea input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function textarea($name, $value = null, $options = [])
    {
        $this->type = 'textarea';

        if (! isset($options['name'])) {
            $options['name'] = $name;
        }

        // Next we will look for the rows and cols attributes, as each of these are put
        // on the textarea element definition. If they are not present, we will just
        // assume some sane default values for these attributes for the developer.
        $options = $this->setTextAreaSize($options);

        $options['id'] = $this->getIdAttribute($name, $options);

        $value = (string) $this->getValueAttribute($name, $value);

        unset($options['size']);

        // Next we will convert the attributes into a string form. Also we have removed
        // the size attribute, as it was merely a short-cut for the rows and cols on
        // the element. Then we'll create the final textarea elements HTML for us.
        $options = $this->attributesToHtml($options);

        return $this->toHtmlString('<textarea' . $options . '>' . e($value, false). '</textarea>');
    }

    /**
     * Set the text area size on the attributes.
     *
     * @param  array $options
     *
     * @return array
     */
    protected function setTextAreaSize($options)
    {
        if (isset($options['size'])) {
            return $this->setQuickTextAreaSize($options);
        }

        // If the "size" attribute was not specified, we will just look for the regular
        // columns and rows attributes, using sane defaults if these do not exist on
        // the attributes array. We'll then return this entire options array back.
        $cols = Arr::get($options, 'cols', 50);

        $rows = Arr::get($options, 'rows', 10);

        return array_merge($options, compact('cols', 'rows'));
    }

    /**
     * Set the text area size using the quick "size" attribute.
     *
     * @param  array $options
     *
     * @return array
     */
    protected function setQuickTextAreaSize($options)
    {
        $segments = explode('x', $options['size']);

        return array_merge($options, ['cols' => $segments[0], 'rows' => $segments[1]]);
    }

    /**
     * Create a select box field.
     *
     * @param  string $name
     * @param  array  $list
     * @param  string|bool $selected
     * @param  array  $selectAttributes
     * @param  array  $optionsAttributes
     * @param  array  $optgroupsAttributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function select(
        $name,
        $list = [],
        $selected = null,
        array $selectAttributes = [],
        array $optionsAttributes = [],
        array $optgroupsAttributes = []
    ) {
        $this->type = 'select';

        // When building a select box the "value" attribute is really the selected one
        // so we will use that when checking the model or session for a value which
        // should provide a convenient method of re-populating the forms on post.
        $selected = $this->getValueAttribute($name, $selected);

        $selectAttributes['id'] = $this->getIdAttribute($name, $selectAttributes);

        if (! isset($selectAttributes['name'])) {
            $selectAttributes['name'] = $name;
        }

        // We will simply loop through the options and build an HTML value for each of
        // them until we have an array of HTML declarations. Then we will join them
        // all together into one single HTML element that can be put on the form.
        $html = [];

        if (isset($selectAttributes['placeholder'])) {
            $html[] = $this->placeholderOption($selectAttributes['placeholder'], $selected);
            unset($selectAttributes['placeholder']);
        }

        foreach ($list as $value => $display) {
            $optionAttributes = $optionsAttributes[$value] ?? [];
            $optgroupAttributes = $optgroupsAttributes[$value] ?? [];
            $html[] = $this->getSelectOption($display, $value, $selected, $optionAttributes, $optgroupAttributes);
        }

        // Once we have all of this HTML, we can join this into a single element after
        // formatting the attributes into an HTML "attributes" string, then we will
        // build out a final select statement, which will contain all the values.
        $selectAttributes = $this->attributesToHtml($selectAttributes);

        $list = implode('', $html);

        return $this->toHtmlString("<select{$selectAttributes}>{$list}</select>");
    }

    /**
     * Create a select range field.
     *
     * @param  string $name
     * @param  string $begin
     * @param  string $end
     * @param  string $selected
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function selectRange($name, $begin, $end, $selected = null, $options = [])
    {
        $range = array_combine($range = range($begin, $end), $range);

        return $this->select($name, $range, $selected, $options);
    }

    /**
     * Create a select year field.
     *
     * @param  string $name
     * @param  string $begin
     * @param  string $end
     * @param  string $selected
     * @param  array  $options
     *
     * @return mixed
     */
    public function selectYear()
    {
        return call_user_func_array([$this, 'selectRange'], func_get_args());
    }

    /**
     * Create a select month field.
     *
     * @param  string $name
     * @param  string $selected
     * @param  array  $options
     * @param  string $format
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function selectMonth($name, $selected = null, $options = [], $format = '%B')
    {
        $months = [];

        foreach (range(1, 12) as $month) {
            $months[$month] = strftime($format, mktime(0, 0, 0, $month, 1));
        }

        return $this->select($name, $months, $selected, $options);
    }

    /**
     * Get the select option for the given value.
     *
     * @param  string $display
     * @param  string $value
     * @param  string $selected
     * @param  array  $attributes
     * @param  array  $optgroupAttributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function getSelectOption($display, $value, $selected, array $attributes = [], array $optgroupAttributes = [])
    {
        if (is_iterable($display)) {
            return $this->optionGroup($display, $value, $selected, $optgroupAttributes, $attributes);
        }

        return $this->option($display, $value, $selected, $attributes);
    }

    /**
     * Create an option group form element.
     *
     * @param  array  $list
     * @param  string $label
     * @param  string $selected
     * @param  array  $attributes
     * @param  array  $optionsAttributes
     * @param  integer  $level
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function optionGroup($list, $label, $selected, array $attributes = [], array $optionsAttributes = [], $level = 0)
    {
        $html = [];
        $space = str_repeat("&nbsp;", $level);
        foreach ($list as $value => $display) {
            $optionAttributes = $optionsAttributes[$value] ?? [];
            if (is_iterable($display)) {
                $html[] = $this->optionGroup($display, $value, $selected, $attributes, $optionAttributes, $level+5);
            } else {
                $html[] = $this->option($space.$display, $value, $selected, $optionAttributes);
            }
        }
        return $this->toHtmlString('<optgroup label="' . e($space.$label, false) . '"' . $this->attributesToHtml($attributes) . '>' . implode('', $html) . '</optgroup>');
    }

    /**
     * Create a select element option.
     *
     * @param  string $display
     * @param  string $value
     * @param  string $selected
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function option($display, $value, $selected, array $attributes = [])
    {
        $selected = $this->getSelectedValue($value, $selected);

        $options = array_merge(['value' => $value, 'selected' => $selected], $attributes);

        $string = '<option' . $this->attributesToHtml($options) . '>';
        if ($display !== null) {
            $string .= e($display, false) . '</option>';
        }

        return $this->toHtmlString($string);
    }

    /**
     * Create a placeholder select element option.
     *
     * @param $display
     * @param $selected
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function placeholderOption($display, $selected)
    {
        $selected = $this->getSelectedValue(null, $selected);

        $options = [
            'selected' => $selected,
            'value' => '',
        ];

        return $this->toHtmlString('<option' . $this->attributesToHtml($options) . '>' . e($display, false) . '</option>');
    }

    /**
     * Determine if the value is selected.
     *
     * @param  string $value
     * @param  string $selected
     *
     * @return null|string
     */
    protected function getSelectedValue($value, $selected)
    {
        if (is_array($selected)) {
            return in_array($value, $selected, true) || in_array((string) $value, $selected, true) ? 'selected' : null;
        } elseif ($selected instanceof Collection) {
            return $selected->contains($value) ? 'selected' : null;
        }
        if (is_int($value) && is_bool($selected)) {
            return (bool)$value === $selected;
        }
        return ((string) $value === (string) $selected) ? 'selected' : null;
    }

    /**
     * Create a checkbox input field.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function checkbox($name, $value = 1, $checked = null, $options = [])
    {
        return $this->checkable('checkbox', $name, $value, $checked, $options);
    }

    /**
     * Create a radio button input field.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function radio($name, $value = null, $checked = null, $options = [])
    {
        if (is_null($value)) {
            $value = $name;
        }

        return $this->checkable('radio', $name, $value, $checked, $options);
    }

    /**
     * Create a checkable input field.
     *
     * @param  string $type
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function checkable($type, $name, $value, $checked, $options)
    {
        $this->type = $type;

        $checked = $this->getCheckedState($type, $name, $value, $checked);

        if ($checked) {
            $options['checked'] = 'checked';
        }

        return $this->input($type, $name, $value, $options);
    }

    /**
     * Get the check state for a checkable input.
     *
     * @param  string $type
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     *
     * @return bool
     */
    protected function getCheckedState($type, $name, $value, $checked)
    {
        switch ($type) {
            case 'checkbox':
                return $this->getCheckboxCheckedState($name, $value, $checked);

            case 'radio':
                return $this->getRadioCheckedState($name, $value, $checked);

            default:
                return $this->compareValues($name, $value);
        }
    }

    /**
     * Get the check state for a checkbox input.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     *
     * @return bool
     */
    protected function getCheckboxCheckedState($name, $value, $checked)
    {
        $request = $this->request($name);

        if (isset($this->session) && ! $this->oldInputIsEmpty() && is_null($this->old($name)) && !$request) {
            return false;
        }

        if ($this->missingOldAndModel($name) && is_null($request)) {
            return $checked;
        }

        $posted = $this->getValueAttribute($name, $checked);

        if (is_array($posted)) {
            return in_array($value, $posted);
        } elseif ($posted instanceof Collection) {
            return $posted->contains('id', $value);
        } else {
            return (bool) $posted;
        }
    }

    /**
     * Get the check state for a radio input.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     *
     * @return bool
     */
    protected function getRadioCheckedState($name, $value, $checked)
    {
        $request = $this->request($name);

        if ($this->missingOldAndModel($name) && !$request) {
            return $checked;
        }

        return $this->compareValues($name, $value);
    }

    /**
     * Determine if the provide value loosely compares to the value assigned to the field.
     * Use loose comparison because Laravel model casting may be in affect and therefore
     * 1 == true and 0 == false.
     *
     * @param  string $name
     * @param  string $value
     * @return bool
     */
    protected function compareValues($name, $value)
    {
        return $this->getValueAttribute($name) == $value;
    }

    /**
     * Determine if old input or model input exists for a key.
     *
     * @param  string $name
     *
     * @return bool
     */
    protected function missingOldAndModel($name)
    {
        return (is_null($this->old($name)) && is_null($this->getModelValueAttribute($name)));
    }

    /**
     * Create a HTML reset input element.
     *
     * @param  string $value
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function reset($value, $attributes = [])
    {
        return $this->input('reset', null, $value, $attributes);
    }

    /**
     * Create a HTML image input element.
     *
     * @param  string $url
     * @param  string $name
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function image($url, $name = null, $attributes = [])
    {
        $attributes['src'] = $this->url->asset($url);

        return $this->input('image', $name, null, $attributes);
    }

    /**
     * Create a month input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function month($name, $value = null, $options = [])
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m');
        }

        return $this->input('month', $name, $value, $options);
    }

    /**
     * Create a color input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function color($name, $value = null, $options = [])
    {
        return $this->input('color', $name, $value, $options);
    }

    /**
     * Create a submit button element.
     *
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function submit($value = null, $options = [])
    {
        return $this->input('submit', null, $value, $options);
    }

    /**
     * Create a button element.
     *
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function button($value = null, $options = [])
    {
        if (! array_key_exists('type', $options)) {
            $options['type'] = 'button';
        }

        return $this->toHtmlString('<button' . $this->attributesToHtml($options) . '>' . $value . '</button>');
    }

    /**
     * Create a datalist box field.
     *
     * @param  string $id
     * @param  array  $list
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function datalist($id, $list = [])
    {
        $this->type = 'datalist';

        $attributes['id'] = $id;

        $html = [];

        if ($this->isAssociativeArray($list)) {
            foreach ($list as $value => $display) {
                $html[] = $this->option($display, $value, null, []);
            }
        } else {
            foreach ($list as $value) {
                $html[] = $this->option($value, $value, null, []);
            }
        }

        $attributes = $this->attributesToHtml($attributes);

        $list = implode('', $html);

        return $this->toHtmlString("<datalist{$attributes}>{$list}</datalist>");
    }

    /**
     * Determine if an array is associative.
     *
     * @param  array $array
     * @return bool
     */
    protected function isAssociativeArray($array)
    {
        return (array_values($array) !== $array);
    }

    /**
     * Parse the form action method.
     *
     * @param  string $method
     *
     * @return string
     */
    protected function getMethod($method)
    {
        $method = strtoupper($method);

        return $method !== 'GET' ? 'POST' : $method;
    }

    /**
     * Get the form action from the options.
     *
     * @param  array $options
     *
     * @return string
     */
    protected function getAction(array $options)
    {
        // We will also check for a "route" or "action" parameter on the array so that
        // developers can easily specify a route or controller action when creating
        // a form providing a convenient interface for creating the form actions.
        if (isset($options['url'])) {
            return $this->getUrlAction($options['url']);
        }

        if (isset($options['route'])) {
            return $this->getRouteAction($options['route']);
        }

        // If an action is available, we are attempting to open a form to a controller
        // action route. So, we will use the URL generator to get the path to these
        // actions and return them from the method. Otherwise, we'll use current.
        elseif (isset($options['action'])) {
            return $this->getControllerAction($options['action']);
        }

        return $this->url->current();
    }

    /**
     * Get the action for a "url" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getUrlAction($options)
    {
        if (is_array($options)) {
            return $this->url->to($options[0], array_slice($options, 1));
        }

        return $this->url->to($options);
    }

    /**
     * Get the action for a "route" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getRouteAction($options)
    {
        if (is_array($options)) {
            $parameters = array_slice($options, 1);

            if (array_keys($options) === [0, 1]) {
                $parameters = head($parameters);
            }

            return $this->url->route($options[0], $parameters);
        }

        return $this->url->route($options);
    }

    /**
     * Get the action for an "action" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getControllerAction($options)
    {
        if (is_array($options)) {
            return $this->url->action($options[0], array_slice($options, 1));
        }

        return $this->url->action($options);
    }

    /**
     * Get the ID attribute for a field name.
     *
     * @param  string $name
     * @param  array  $attributes
     *
     * @return string
     */
    public function getIdAttribute($name, $attributes)
    {
        if (array_key_exists('id', $attributes)) {
            return $attributes['id'];
        }

        if (in_array($name, $this->labels)) {
            return $name;
        }
    }

    /**
     * Get the value that should be assigned to the field.
     *
     * @param  string $name
     * @param  string $value
     *
     * @return mixed
     */
    public function getValueAttribute($name, $value = null)
    {
        if (is_null($name)) {
            return $value;
        }

        $old = $this->old($name);

        if (! is_null($old) && $name !== '_method') {
            return $old;
        }

        if (function_exists('app')) {
            $hasNullMiddleware = app("Illuminate\Contracts\Http\Kernel")
                ->hasMiddleware(ConvertEmptyStringsToNull::class);

            if ($hasNullMiddleware
                && is_null($old)
                && is_null($value)
                && !is_null($this->view->shared('errors'))
                && count(is_countable($this->view->shared('errors')) ? $this->view->shared('errors') : []) > 0
            ) {
                return null;
            }
        }

        $request = $this->request($name);
        if (! is_null($request) && $name != '_method') {
            return $request;
        }

        if (! is_null($value)) {
            return $value;
        }

        if (isset($this->model)) {
            return $this->getModelValueAttribute($name);
        }
    }

    /**
     * Take Request in fill process
     * @param bool $consider
     */
    public function considerRequest($consider = true)
    {
        $this->considerRequest = $consider;
    }

    /**
     * Get value from current Request
     * @param $name
     * @return array|null|string
     */
    protected function request($name)
    {
        if (!$this->considerRequest) {
            return null;
        }

        if (!isset($this->request)) {
            return null;
        }

        return $this->request->input($this->transformKey($name));
    }

    /**
     * Get the model value that should be assigned to the field.
     *
     * @param  string $name
     *
     * @return mixed
     */
    protected function getModelValueAttribute($name)
    {
        $key = $this->transformKey($name);

        if ((is_string($this->model) || is_object($this->model)) && method_exists($this->model, 'getFormValue')) {
            return $this->model->getFormValue($key);
        }

        return data_get($this->model, $key);
    }

    /**
     * Get a value from the session's old input.
     *
     * @param  string $name
     *
     * @return mixed
     */
    public function old($name)
    {
        if (isset($this->session)) {
            $key = $this->transformKey($name);
            $payload = $this->session->getOldInput($key);

            if (!is_array($payload)) {
                return $payload;
            }

            if (!in_array($this->type, ['select', 'checkbox'])) {
                if (!isset($this->payload[$key])) {
                    $this->payload[$key] = collect($payload);
                }

                if (!empty($this->payload[$key])) {
                    $value = $this->payload[$key]->shift();
                    return $value;
                }
            }

            return $payload;
        }
    }

    /**
     * Determine if the old input is empty.
     *
     * @return bool
     */
    public function oldInputIsEmpty()
    {
        return (isset($this->session) && count((array) $this->session->getOldInput()) === 0);
    }

    /**
     * Transform key from array to dot syntax.
     *
     * @param  string $key
     *
     * @return mixed
     */
    protected function transformKey($key)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
    }

    /**
     * Transform the string to an Html serializable object
     *
     * @param $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }

    private function entities(string $value): string
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    public function attributesToHtml(array $attributes): string
    {
        $html = [];
        foreach ($attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);
            if (! is_null($element)) {
                $html[] = $element;
            }
        }
        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    protected function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        // This will convert HTML attributes such as "required" to a correct
        // form instead of using incorrect numerics.
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }

        if (is_array($value) && $key === 'class') {
            return 'class="' . implode(' ', $value) . '"';
        }

        if (! is_null($value)) {
            return $key . '="' . e($value, false) . '"';
        }
    }
}
