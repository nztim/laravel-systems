<?php declare(strict_types=1);

namespace NZTim\Markdown;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class MarkdownServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('nztim-markdown-converter', function () {
            return (new ParsedownExtraWithYouTubeEmbed())
                ->setBreaksEnabled(true)
                ->setSafeMode(true)
                ->setUrlsLinked(true);
        });
    }

    public function boot()
    {
        $blade = app(BladeCompiler::class);
        $blade->directive('markdown', function ($string) {
            return "<?php echo markdown($string); ?>";
        });
    }
}
