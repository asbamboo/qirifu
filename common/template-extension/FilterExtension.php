<?php
namespace asbamboo\qirifu\common\templateExtension;

use asbamboo\template\Extension;
use asbamboo\framework\template\Template;
use asbamboo\template\Filters;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月1日
 */
class FilterExtension extends Extension
{

    /**
     *
     * @var Template
     */
    private $Template;

    /**
     *
     * @param Template $Template
     */
    public function __construct(Template $Template)
    {
        $this->Template = $Template;
    }

    /**
     *
     * {@inheritDoc}
     * @see Extension::getFunctions()
     */
    public function getFilters()
    {
        return [
            new filters('code_label', [$this, 'codeLabel']),
        ];
    }

    /**
     *
     * @param string $code
     * @param string $entity_name
     */
    public function codeLabel($code_value, $entity_name, $code_name)
    {
        $codes   = eval("return asbamboo\\qirifu\\common\\model\\{$entity_name}\\Code::{$code_name};");
        return $codes[$code_value];
    }
}
