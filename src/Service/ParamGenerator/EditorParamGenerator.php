<?php

namespace App\Service\ParamGenerator;

use App\Service\DateTimeService;
use App\Service\NavbarFactory\EditorNavbarFactory;

/**
 * Description of EditorParamGenerator
 *
 * @author Draeius
 */
class EditorParamGenerator extends ParamGenerator {

    /**
     *
     * @var \App\Service\EditorNavbarFactory
     */
    private $navbarFactory;

    function __construct(DateTimeService $dtService, EditorNavbarFactory $navbarFactory) {
        parent::__construct($dtService);
        $this->navbarFactory = $navbarFactory;
    }

    public function getEditorParams($page) {
        return array_merge(parent::getBaseParams('Editor', $this->navbarFactory->getDefaultNavbar()), [
            'page' => $page
        ]);
    }

}
