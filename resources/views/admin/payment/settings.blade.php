@extends('admin.layout.base')

@section('title', 'Payment Settings ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <form action="{{route('admin.settings.payment.store')}}" method="POST">
                {{csrf_field()}}
                <h5>@lang('admin.payment_mode') </h5>
                <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-cc-stripe pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="stripe_secret_key" class="col-form-label">
                                    @lang('admin.st_card_pay')
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('CARD') == 1) checked  @endif  name="CARD" id="stripe_check" onchange="cardselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                        <div id="card_field" @if(Setting::get('CARD') == 0) style="display: none;" @endif>
                            <div class="form-group row">
                                <label for="stripe_secret_key" class="col-xs-4 col-form-label">@lang('admin.stripe_secret_key')</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('stripe_secret_key', '') }}" name="stripe_secret_key" id="stripe_secret_key"  placeholder="@lang('admin.stripe_secret_key')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stripe_publishable_key" class="col-xs-4 col-form-label">@lang('admin.stripe_publishable_key')</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('stripe_publishable_key', '') }}" name="stripe_publishable_key" id="stripe_publishable_key"  placeholder="@lang('admin.stripe_publishable_key')">
                                </div>
                            </div>
                        </div>
                    </blockquote>
                </div>

                <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-money pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="cash-payments" class="col-form-label">
                                    "@lang('admin.cash_payments')
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('CASH') == 1) checked  @endif name="CASH" id="cash-payments" onchange="cardselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                    </blockquote>
                </div>
                <h5>"@lang('admin.payment_settings')</h5>

                <div class="card card-block card-inverse card-info">
                    <blockquote class="card-blockquote">
                        <div class="form-group row">
                            <label for="daily_target" class="col-xs-4 col-form-label">@lang('admin.daily_target')(%)</label>
                            <div class="col-xs-8">
                                <input class="form-control" 
                                    type="float"
                                    value="{{ Setting::get('daily_target', '0')  }}"
                                    id="daily_target"
                                    name="daily_target"
                                    min="0"
                                    required
                                    placeholder="@lang('admin.daily_target')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="daily_target" class="col-xs-4 col-form-label">@lang('admin.pay_for_ad')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="numder"
                                    value="{{ Setting::get('pay_for_ad', '0')  }}"
                                    id="pay_for_ad"
                                    name="pay_for_ad"
                                    min="0"
                                    required
                                    placeholder="@lang('admin.pay_for_ad')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tax_percentage" class="col-xs-4 col-form-label">@lang('admin.tax_percentage')(%)</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="float"
                                    value="{{ Setting::get('tax_percentage', '0')  }}"
                                    id="tax_percentage"
                                    name="tax_percentage"
                                    min="0"
                                    max="100"
                                    placeholder="@lang('admin.tax_percentage')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surge_trigger" class="col-xs-4 col-form-label">@lang('admin.surge_trigger_point')(%)</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="float"
                                    value="{{ Setting::get('surge_trigger', '')  }}"
                                    id="surge_trigger"
                                    name="surge_trigger"
                                    min="0"
                                    required
                                    placeholder="@lang('admin.surge_trigger_point')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surge_percentage" class="col-xs-4 col-form-label">@lang('admin.surge_percentage')(%)</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="float"
                                    value="{{ Setting::get('surge_percentage', '0')  }}"
                                    id="surge_percentage"
                                    name="surge_percentage"
                                    min="0"
                                    max="100"
                                    placeholder="@lang('admin.surge_percentage')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="commission_percentage" class="col-xs-4 col-form-label">@lang('admin.commission_percentage')(%)</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="float"
                                    value="{{ Setting::get('commission_percentage', '0') }}"
                                    id="commission_percentage"
                                    name="commission_percentage"
                                    min="0"
                                    max="100"
                                    placeholder="@lang('admin.commission_percentage') ">
                            </div>
                        </div>
                       {{-- <div class="form-group row">
                            <label for="sub_com" class="col-xs-4 col-form-label">@lang('admin.sub_com')(LE)</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="float"
                                    value="{{ Setting::get('sub_com', '0') }}"
                                    id="sub_com"
                                    name="sub_com"
                                    min="0"
                                    max="100"
                                    placeholder="@lang('admin.sub_com') ">
                            </div>
                        </div>--}}
                        <div class="form-group row">
                            <label for="booking_prefix" class="col-xs-4 col-form-label">@lang('admin.booking_id_prefix')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="text"
                                    value="{{ Setting::get('booking_prefix', '0') }}"
                                    id="booking_prefix"
                                    name="booking_prefix"
                                    min="0"
                                    max="4"
                                    placeholder="@lang('admin.booking_id_prefix')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="base_price" class="col-xs-4 col-form-label">
                                @lang('admin.currency') ( <strong>{{ Setting::get('currency', '$')  }} </strong>)
                            </label>
                            <div class="col-xs-8">
                                <select name="currency" class="form-control" required>
                                    <option @if(Setting::get('currency') == "EGP") selected @endif value="EGP"> @lang('admin.e_p')</option>
                                    <option @if(Setting::get('currency') == "$") selected @endif value="$"> @lang('admin.us_do')</option>
                                    <option @if(Setting::get('currency') == "₹") selected @endif value="₹"> @lang('admin.in_ru')</option>
                                    <option @if(Setting::get('currency') == "د.ك") selected @endif value="د.ك"> @lang('admin.ku_di')</option>
                                    <option @if(Setting::get('currency') == "د.ب") selected @endif value="د.ب"> @lang('admin.ba_di')</option>
                                    <option @if(Setting::get('currency') == "﷼") selected @endif value="﷼"> @lang('admin.om_ri')</option>
                                    <option @if(Setting::get('currency') == "£") selected @endif value="£"> @lang('admin.br_pou')</option>
                                    <option @if(Setting::get('currency') == "€") selected @endif value="€"> @lang('admin.euro')</option>
                                    <option @if(Setting::get('currency') == "CHF") selected @endif value="CHF"> @lang('admin.s_f')</option>
                                    <option @if(Setting::get('currency') == "ل.د") selected @endif value="ل.د"> @lang('admin.li_di')</option>
                                    <option @if(Setting::get('currency') == "B$") selected @endif value="B$"> @lang('admin.br_do')</option>
                                    <option @if(Setting::get('currency') == "S$") selected @endif value="S$"> @lang('admin.si_do')</option>
                                    <option @if(Setting::get('currency') == "AU$") selected @endif value="AU$">  @lang('admin.a_do')</option>
                                </select>
                            </div>
                        </div>
                    </blockquote>
                </div>

                <div class="form-group row">
                    <div class="col-xs-4">
                        <a href="{{ route('admin.index') }}" class="btn btn-warning btn-block">@lang('admin.back')</a>
                    </div>
                    @can('تعديل اعدادات الدفع')
                    <div class="offset-xs-4 col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block">@lang('admin.up_sit_set')</button>
                    </div>
                        @endcan
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
function cardselect()
{
    if($('#stripe_check').is(":checked")) {
        $("#card_field").fadeIn(700);
    } else {
        $("#card_field").fadeOut(700);
    }
}
</script>
@endsection