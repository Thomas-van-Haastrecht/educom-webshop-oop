<?php

class HtmlDoc {
    private function showHtmlStart() {
        echo '<!doctype html>' . PHP_EOL;
        echo '<html>' . PHP_EOL; 
    }

    private function showHeadStart() {
        echo '    <head>' . PHP_EOL;
    }

    protected function showHeadContent() {
        echo '        <link rel="stylesheet" href="css/stylesheet.css">' . PHP_EOL;
    }

    private function showHeadEnd() {
        echo '    </head>' . PHP_EOL;
    }

    private function showBodyStart() {
        echo '    <body>' . PHP_EOL;
    }

    protected function showBodyContent() {

    }

    private function showBodyEnd() {
        echo '    </body>' . PHP_EOL;
    }

    private function showHtmlEnd() {
        echo '</html>'; 
    }


    public function show() {
        $this->showHtmlStart();
        $this->showHeadStart();
        $this->showHeadContent();
        $this->showHeadEnd();
        $this->showBodyStart();
        $this->showBodyContent();
        $this->showBodyEnd();
        $this->showHtmlEnd();
    }
}

?>