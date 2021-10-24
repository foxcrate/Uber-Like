@inject('models', 'App\Model\Governorate')
<style>
    .select2-container--default .select2-selection--single {
        height: calc(2.5rem + 2px);
        color: black;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 30px;
        font-weight: bold;
    }
</style>
@if(direction() == 'ltr')
@else
    <style>
        .select2-container--default .select2-results > .select2-results__options {
            text-align: right;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            float: right;
        }
    </style>
@endif
<div class="form-group">
    <label for="name">الاسم</label>
    {!! Form::text('name', null , [
        'class' => 'form-control'
    ]) !!}
</div>

<div class="form-group">
    <label for="governorate_id">المحافظات</label>
    {!! Form::select('governorate_id',$models->pluck('name', 'id') , old('governorate_id'),
     ['class'=>'form-control custom-select custom-select-lg mb-3 mt-3 custom-width select2', 'placeholder' => '..............']) !!}
</div>

<div class="form-group">
    <button class="btn btn-primary" type="submit">Submit</button>
</div>
