@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Settings')   
@section('content')
    <section id="basic-input">
        <div class="row" id="basic-table">
            <div class="col-12">
                <div class="card">
                    <x-cardHeader :href="route('settings.index')" name="Setting" />
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">General
                                    Setting</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">SEO
                                    Setting</button>
                            </li>
                        </ul>


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <form action="{{ route('settings.update') }}" method="POST">
                                    @csrf
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Key</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($settings as $setting)
                                              
                                                    <tr>
                                                        <td class="text-capitalize">{{ $setting->key }}</td>
                                                        <td>
                                                            @if ($setting->type == 'image')
                                                                <x-filemanager :value="$setting->value" :name="$setting->key">
                                                                </x-filemanager>
                                                            @else
                                                                <input type="{{ $setting->type }}"
                                                                    class="form-control form-control-sm"
                                                                    name="{{ $setting->key }}" id=""
                                                                    aria-describedby="helpId" placeholder=""
                                                                    value="{{ $setting->value }}">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <td colspan="2">
                                                    <x-dashboard.button></x-dashboard.button>
                                                </td>
                                            </tfoot>
                                        </table>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <form action="{{ route('seo.update', @$seo->id) }}" method="POST">
                                    @method('PATCH')
                                    @csrf
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <inputelement name="meta_title" placeholder="Macbook Pro 2019"
                                                    value="{{ old('meta_title', @$seo->meta_title) }}"
                                                    :errors="{{ json_encode($errors->toArray()) }}" required="true">
                                                </inputelement>

                                                <x-filemanager :value="@$seo->og_image" :name="'og_image'">
                                                </x-filemanager>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Meta
                                                        Keywords:</label><small class="text-danger">*</small>
                                                    <textarea class="form-control" rows="2" name="meta_keywords">{{ old('meta_keywords', @$seo->meta_keywords) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Meta Description:</label><small class="text-danger">*</small>
                                                    <textarea class="form-control" rows="5" name="meta_description">{{ old('meta_description', @$seo->meta_description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <x-dashboard.button></x-dashboard.button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection
