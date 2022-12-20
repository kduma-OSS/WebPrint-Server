<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector;
use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector;
use Rector\CodingStyle\Rector\Property\AddFalseDefaultToBoolPropertyRector;
use Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryAndToEarlyReturnRector;
use Rector\Php80\Rector\FunctionLike\UnionTypesRector;
use Rector\Php81\Rector\ClassMethod\NewInInitializerRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/lang',
        __DIR__.'/public',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ]);

    // register a single rule
    $rectorConfig->rule(ActionInjectionToConstructorInjectionRector::class);

//    $rectorConfig->importNames();
//    $rectorConfig->importShortClasses(false);

    // define sets of rules
    $rectorConfig->sets([
        SetList::PSR_4,
//      SetList::PHP_52,
//      SetList::PHP_53,
//      SetList::PHP_54,
//      SetList::PHP_55,
//      SetList::PHP_56,
//      SetList::PHP_70,
//      SetList::PHP_71,
//      SetList::PHP_72,
//      SetList::PHP_73,
//      SetList::PHP_74,
//      SetList::PHP_80,
        SetList::PHP_81,
        SetList::PHP_80,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::MYSQL_TO_MYSQLI,
        SetList::TYPE_DECLARATION,
        SetList::GMAGICK_TO_IMAGICK,
        SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION,

        // Skipped
        //  SetList::PRIVATIZATION,
        //  SetList::NAMING,
    ]);

    $rectorConfig->skip([
        // To Skip
        NullToStrictStringFuncCallArgRector::class, // SetList::PHP_81
        UnionTypesRector::class, // SetList::PHP_80
        SingleInArrayToCompareRector::class, // SetList::CODE_QUALITY
        SimplifyBoolIdenticalTrueRector::class, // SetList::CODE_QUALITY
        AddFalseDefaultToBoolPropertyRector::class, // SetList::CODING_STYLE
        SymplifyQuoteEscapeRector::class, // SetList::CODING_STYLE
        UnSpreadOperatorRector::class, // SetList::CODING_STYLE
        ChangeAndIfToEarlyReturnRector::class, // SetList::EARLY_RETURN
        ChangeOrIfReturnToEarlyReturnRector::class, // SetList::EARLY_RETURN

        // To Decide Later
        StaticArrowFunctionRector::class, // SetList::CODING_STYLE
        StaticClosureRector::class, // SetList::CODING_STYLE
        ReturnBinaryAndToEarlyReturnRector::class, // SetList::EARLY_RETURN
        ArrayShapeFromConstantArrayReturnRector::class, // SetList::TYPE_DECLARATION

        // To Decide

        // Broken
        CommonNotEqualRector::class,
        ConsistentPregDelimiterRector::class,
        NewInInitializerRector::class,
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);
};
