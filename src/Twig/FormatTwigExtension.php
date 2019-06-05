<?php

namespace App\Twig;

use App\Service\FormatService;
use Twig\Extension\AbstractExtension;
use Twig_SimpleFilter;

/**
 * Description of FormatTwigExtension
 *
 * @author Draeius
 */
class FormatTwigExtension extends AbstractExtension {

    /**
     *
     * @var FormatService
     */
    private $formatter;

    public function __construct(FormatService $formatter) {
        $this->formatter = $formatter;
    }

    public function getFilters() {
        return array(
            new Twig_SimpleFilter('colorize', array($this, 'formatFilter'))
        );
    }

    public function formatFilter($text, $allowedOnly = false, $isPost = false) {
        $result = $this->formatter->parse($text, $allowedOnly);
        $sugarDay = false;
        if ($sugarDay && !$isPost) {
            return str_replace(['Gold', 'Platin', 'Edelsteine'], ['Kekse', 'Geschenke', 'Weihnachtssterne'], $result);
        }
        return $result;
    }

    public function getName() {
        return 'format_twig_extension';
    }

}
