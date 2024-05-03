<?php namespace NZTim\Helpers;

use Collective\Html\HtmlServiceProvider;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as LaravelValidator;
use Illuminate\Validation\Validator;
use Illuminate\View\Compilers\BladeCompiler;

class HelpersServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create('en_NZ');
            $faker->addProvider(new FakerSafeEmailProvider($faker));
            return $faker;
        });
        $this->app->register(HtmlServiceProvider::class);
    }

    public function boot()
    {
        // Blade directives ---------------------------------------------------

        $blade = app(BladeCompiler::class);

        $blade->directive('nl2br', function ($string) {
            return "<?php echo nl2br(sanitize($string)); ?>";
        });

        $blade->directive('autolink', function ($string) {
            return "<?php echo nl2br(autolink(sanitize($string))); ?>";
        });

        $blade->directive('pagination', function ($paginator) {
            return "<?php echo with($paginator)->appends(request()->except('page'))->render(); ?>";
        });

        $blade->directive('formerror', function ($label) {
            return '<?php echo $errors->first(' . $label . ', \'<div class="alert alert-danger">:message</div>\'); ?>';
        });

        // Validation ---------------------------------------------------------

        /** @var LaravelValidator $laravelValidator */
        $laravelValidator = app(LaravelValidator::class);

        // Common passwords validator, based on https://github.com/unicodeveloper/laravel-password
        $validate = function ($attribute, $value, $parameters, $validator) {
            $path = realpath(__DIR__ . '/common-passwords.txt');
            return !collect(explode("\n", str_replace("\r\n", "\n", file_get_contents($path))))->contains($value);
        };
        $laravelValidator->extend('commonpwd', $validate, 'This password is too common, please try a different one.');

        // File extension validator
        $validate = function ($attribute, $value, $parameters, $validator) {
            /** @var UploadedFile $value */
            return in_array(strtolower($value->getClientOriginalExtension()), $parameters);
        };
        $laravelValidator->extend('fileext', $validate, 'Invalid file extension');

        // Date after or equal validator
        $validate = function ($attribute, $value, $parameters, $validator) {
            /** @var \Illuminate\Validation\Validator $validator */
            $referenceDate = Arr::get($validator->getData(), $parameters[0], date('Y-m-d'));
            return strtotime($value) >= strtotime($referenceDate);
        };
        $laravelValidator->extend('after_or_equal', $validate, 'Invalid date');

        // UTF-8 string validator
        $validate = function ($attribute, $value, $parameters, Validator $validator) {
            return mb_check_encoding($value, 'UTF-8');
        };
        $laravelValidator->extend('utf8', $validate, 'Invalid input');

        // Commands -----------------------------------------------------------

        $this->commands([
            EnvCheckCommand::class,
        ]);
    }
}
