<?php namespace spec\Arrilot\Widgets\Factories;

use App\Widgets\TestDefaultSlider;
use App\Widgets\TestMyClass;
use App\Widgets\TestWidgetWithDIInRun;
use App\Widgets\TestWidgetWithParamsInRun;
use Arrilot\Widgets\Misc\Wrapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Arrilot\Widgets\Dummies\Slider;

class WidgetFactorySpec extends ObjectBehavior {

    protected $config = [
        'defaultNamespace' => 'App\Widgets',
        'customNamespaces' => [
                'slider'          => 'spec\Arrilot\Widgets\Dummies',
                'testWidgetName'  => ''
            ]
        ];


    function let(Wrapper $wrapper)
    {
        $this->beConstructedWith($this->config, $wrapper);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\Factories\WidgetFactory');
    }


    function it_can_run_widget_from_default_namespace(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestDefaultSlider([]), 'run'], [])
        );
        $this->testDefaultSlider()->shouldReturn("Default test slider was executed with \$slides = 6");
    }


    function it_can_run_widget_from_custom_namespace(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider([]), 'run'], [])
        );
        $this->slider()->shouldReturn("Slider was executed with \$slides = 6");
    }


    function it_provides_config_override(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider(['slides' => 5]), 'run'], ['slides' => 5])
        );
        $this->slider(['slides' => 5])->shouldReturn("Slider was executed with \$slides = 5");
    }


    function it_throws_exception_for_bad_widget_class()
    {
        $this->shouldThrow('\Arrilot\Widgets\InvalidWidgetClassException')->during('testBadSlider');
    }

    function it_can_run_widgets_with_additional_params(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestWidgetWithParamsInRun([]), 'run'], ['asc'])
        );
        $this->testWidgetWithParamsInRun([], 'asc')->shouldReturn("TestWidgetWithParamsInRun was executed with \$flag = asc");
    }

    function it_can_run_widgets_with_method_injection(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestWidgetWithDIInRun([]), 'run'], [new TestMyClass])
        );
        $this->testWidgetWithParamsInRun()->shouldReturn("bar");
    }
}
