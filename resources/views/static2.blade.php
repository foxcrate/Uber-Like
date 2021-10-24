@extends('user.layout.app')

@section('content')

    @if(app()->getLocale()=="ar")
<div class="row gray-section no-margin" dir="rtl">
    <div class="container">
        <div class="content-block">
            <h2>{{  Setting::get($page,'شروط الخدمة') }}</h2>
            <div class="title-divider"></div>

            <section id="content" >
                @foreach(App\ServiceConditions::all() as $item)
                    <div style="display:{{ ($item->status)?'block':'none' }}">
                        <b>
                            -{{ $item->title }}
                        </b><br />
                        {!! $item->details !!}
                        <br />
                        <br />
                    </div>
                @endforeach
                <br /><br />
            </section>
        </div>
    </div>
</div>
@else
<div class="row gray-section no-margin">
    <div class="container">
        <div class="content-block">
            <h2>{{  Setting::get($page,'Service Conditions') }}</h2>
            <div class="title-divider"></div>

            <section id="content" >
                @foreach(App\ServiceConditions::all() as $item)
                    <div style="display:{{ ($item->status)?'block':'none' }}">
                        <b>
                            -{{ $item->title_en }}
                        </b><br />
                        {!! $item->details_en !!}
                        <br />
                        <br />
                    </div>
                @endforeach
                <br /><br />
            </section>
        </div>
    </div>
</div>
    @endif
@endsection