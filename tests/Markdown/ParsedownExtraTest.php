<?php declare(strict_types=1);

use NZTim\Markdown\ParsedownExtraWithYouTubeEmbed;
use PHPUnit\Framework\TestCase;

class ParsedownExtraTest extends TestCase
{
    private function converter(): ParsedownExtraWithYouTubeEmbed
    {
        // Safe Mode = markup escaped plus extra prevention against XSS
        return (new ParsedownExtraWithYouTubeEmbed())->setBreaksEnabled(true)->setSafeMode(true)->setUrlsLinked(true);
    }

    /** @test */
    public function try_to_parse_yt_url()
    {
        $pattern = '#^(?:https?://|//)?(?:www\.|m\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|watch\?.+&v=))([\w-]{11})(?![\w-])#';
        preg_match($pattern, 'https://google.com/youtube/something', $matches);
        $this->assertEquals([], $matches);
        preg_match($pattern, 'Something something https://www.youtube.com/watch?v=zpOULjyy-n8 something', $matches);
        $this->assertEquals([], $matches);
        preg_match($pattern, 'https://www.youtube.com/watch?v=zpOULjyy-n8', $matches);
        $this->assertEquals('zpOULjyy-n8', $matches[1]);
    }

    /** @test */
    public function youtube_embed_url()
    {
        $converter = $this->converter();
        $result = $converter->text("https://www.youtube.com/watch?v=zpOULjyy-n8");
        // <p><div class="video embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/embed/zpOULjyy-n8" frameborder="0" allowfullscreen="1"></iframe></div></p>
//        $this->assertEquals('<p><div class="video embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/embed/zpOULjyy-n8" frameborder="0" allowfullscreen="1"></iframe></div></p>', $result);
        $this->assertEquals('<p><a href="https://www.youtube.com/watch?v=zpOULjyy-n8">https://www.youtube.com/watch?v=zpOULjyy-n8</a></p>', $result);
    }

    /** @test */
    public function youtube_embed_link()
    {
        $converter = $this->converter();
        $result = $converter->text("[](https://www.youtube.com/watch?v=zpOULjyy-n8)");
        // <p><div class="video embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/embed/zpOULjyy-n8" frameborder="0" allowfullscreen="1"></iframe></div></p>
        $this->assertEquals('<p><div class="video embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/embed/zpOULjyy-n8" frameborder="0" allowfullscreen="1"></iframe></div></p>', $result);
    }

    /** @test */
    public function bold_with_spaces()
    {
        $converter = $this->converter();
        $result = $converter->text('This is **pretty normal**');
        $this->assertEquals("<p>This is <strong>pretty normal</strong></p>", $result);
        $result = $converter->text('What about **a trailing space!!! **');
        $this->assertEquals("<p>What about <strong>a trailing space!!! </strong></p>", $result);
    }

    /** @test */
    public function hello_world()
    {
        $converter = $this->converter();
        $result = $converter->text('# Hello World!');
        $this->assertEquals("<h1>Hello World!</h1>", $result);
        $result = $converter->text(<<<EOF
# Hello World!

Paragraph
with a break

EOF
        );
        $this->assertTrue(str_contains($result, "Paragraph<br />\nwith a break"));
    }

    /** @test */
    public function autolinking()
    {
        $converter = $this->converter();
        $result = $converter->text('Check out https://google.com, it looks cool.');
        //<p>Check out <a href="https://google.com">https://google.com</a>, it looks cool.</p>
        $this->assertTrue(str_contains($result, '<a href="https://google.com"'));
    }

    /** @test */
    public function escape_html()
    {
        $converter = $this->converter();
        $result = $converter->text('Hax: <iframe> <script> <?php <wtf>');
        // <p>Hax: &lt;iframe&gt; &lt;script&gt; &lt;?php &lt;wtf&gt;</p>\n
        $this->assertTrue(str_contains($result, '&lt;iframe&gt; &lt;script&gt; &lt;?php &lt;wtf&gt;'));
    }
}
