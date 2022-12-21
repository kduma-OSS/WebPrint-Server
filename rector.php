<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector;
use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\CodingStyle\Rector\Property\AddFalseDefaultToBoolPropertyRector;
use Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryAndToEarlyReturnRector;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php80\Rector\FunctionLike\UnionTypesRector;
use Rector\Php81\Rector\ClassMethod\NewInInitializerRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;
use RectorLaravel\Rector\Class_\AddMockConsoleOutputFalseToConsoleTestsRector;
use RectorLaravel\Rector\Class_\AnonymousMigrationsRector;
use RectorLaravel\Rector\Class_\PropertyDeferToDeferrableProviderToRector;
use RectorLaravel\Rector\Class_\UnifyModelDatesWithCastsRector;
use RectorLaravel\Rector\ClassMethod\AddGenericReturnTypeToRelationsRector;
use RectorLaravel\Rector\FuncCall\FactoryFuncCallToStaticCallRector;
use RectorLaravel\Rector\FuncCall\HelperFuncCallToFacadeClassRector;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\MethodCall\ChangeQueryWhereDateValueWithCarbonRector;
use RectorLaravel\Rector\MethodCall\FactoryApplyingStatesRector;
use RectorLaravel\Rector\MethodCall\RedirectBackToBackHelperRector;
use RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector;
use RectorLaravel\Rector\New_\AddGuardToLoginEventRector;
use RectorLaravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector;
use RectorLaravel\Rector\StaticCall\MinutesToSecondsInCacheRector;
use RectorLaravel\Rector\StaticCall\Redirect301ToPermanentRedirectRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/lang',
        __DIR__.'/public',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ]);

    // register a single rule
    $rectorConfig->rule(ActionInjectionToConstructorInjectionRector::class);
    $rectorConfig->rule(AddGenericReturnTypeToRelationsRector::class);
    $rectorConfig->rule(AddGuardToLoginEventRector::class);
    $rectorConfig->rule(AddMockConsoleOutputFalseToConsoleTestsRector::class);
    $rectorConfig->rule(AnonymousMigrationsRector::class);
    $rectorConfig->rule(ChangeQueryWhereDateValueWithCarbonRector::class);
    $rectorConfig->rule(FactoryApplyingStatesRector::class);
    $rectorConfig->rule(FactoryFuncCallToStaticCallRector::class);
    $rectorConfig->rule(HelperFuncCallToFacadeClassRector::class);
    $rectorConfig->rule(MinutesToSecondsInCacheRector::class);
    $rectorConfig->rule(OptionalToNullsafeOperatorRector::class);
    $rectorConfig->rule(PropertyDeferToDeferrableProviderToRector::class);
    $rectorConfig->rule(Redirect301ToPermanentRedirectRector::class);
    $rectorConfig->rule(RedirectBackToBackHelperRector::class);
    $rectorConfig->rule(RedirectRouteToToRouteHelperRector::class);
    $rectorConfig->rule(RemoveDumpDataDeadCodeRector::class);
    $rectorConfig->rule(UnifyModelDatesWithCastsRector::class);

//    $rectorConfig->importNames();
//    $rectorConfig->importShortClasses(false);

    // define sets of rules
    $rectorConfig->sets([
        SetList::PSR_4,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::MYSQL_TO_MYSQLI,
        SetList::TYPE_DECLARATION,
        SetList::GMAGICK_TO_IMAGICK,
        SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION,

        LevelSetList::UP_TO_PHP_81,

        PHPUnitLevelSetList::UP_TO_PHPUNIT_100,

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
        CountOnNullRector::class, // SetList::PHP_71
        ClosureToArrowFunctionRector::class, // SetList::PHP_74

        // To Decide

        // Broken
        CommonNotEqualRector::class,
        ConsistentPregDelimiterRector::class,
        NewInInitializerRector::class,
        ActionInjectionToConstructorInjectionRector::class,
        MinutesToSecondsInCacheRector::class,

        // Conflicts with Pint
        PostIncDecToPreIncDecRector::class,
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);
};
