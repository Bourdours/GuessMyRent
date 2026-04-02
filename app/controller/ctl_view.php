<?php
class ViewController
{
    private function render(string $view, array $data = []): void
    {
        extract($data);
        require V_SKELETON . 'v_header.html.php';
        require $view;
        require V_SKELETON . 'v_footer.html.php';
    }

    public function home(): void
    {
        $this->render(VIEW . 'v_index.html.php');
    }

        public function info(): void
    {
        $this->render(V_INFO . 'v_info.html.php');
    }
}