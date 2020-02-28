
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="parent_id" class="control-label"><strong>{{ __('menu.parent_id') }}</strong></label>
                                            <select
                                                name="parent_id"
                                                class="form-control {{$errors->has('parent_id') ? "is-invalid": ""}}"
                                                id="parent_id"
                                                required>
                                                <option value="0">{{ __('menu.parent_name') }}</option>
                                                @foreach ($menus as $key => $value)<option value="{{ $value->id }}"{{ (isset($menu->parent_id) && $menu->parent_id == $value->id) ? 'selected' : ''}}{{ old('parent_id') == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                                                @endforeach

                                            </select>
                                            {!! $errors->first('parent_id', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="name" class="control-label"><strong>{{ __('menu.name') }}</strong></label>
                                            <input
                                                class="form-control {{$errors->has('name') ? "is-invalid": ""}}"
                                                name="name" type="text"
                                                id="name"
                                                placeholder="{{ __('menu.name') }}"
                                                value="{{ isset($menu->name) ? $menu->name : old('name') }}"
                                                required>
                                            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="icon" class="control-label"><strong>{{ __('menu.icon') }}</strong></label>
                                            <input
                                                class="form-control {{$errors->has('icon') ? "is-invalid": ""}}"
                                                name="icon" type="text"
                                                id="icon"
                                                placeholder="{{ __('menu.icon') }}"
                                                value="{{ isset($menu->icon) ? $menu->icon : old('icon') }}">
                                            {!! $errors->first('icon', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="url" class="control-label"><strong>{{ __('menu.url') }}</strong></label>
                                            <input
                                                class="form-control {{$errors->has('url') ? "is-invalid": ""}}"
                                                name="url" type="text"
                                                id="url"
                                                placeholder="{{ __('menu.url') }}"
                                                value="{{ isset($menu->url) ? $menu->url : old('url') }}">
                                            {!! $errors->first('url', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="status" class="control-label"><strong>{{ __('menu.status') }}</strong></label>
                                            <select name="status"
                                                class="form-control {{$errors->has('status') ? "is-invalid": ""}}"
                                                id="status"
                                                required>@foreach (json_decode('{"enable":"Enable","disable":"Disable"}', true) as $optionKey => $optionValue)

                                                <option value="{{ $optionKey }}"{{ (isset($menu->status) && $menu->status == $optionKey) ? 'selected' : ''}}{{ old('status') == $optionKey ? 'selected' : ''}}>{{ $optionValue }}</option>@endforeach

                                            </select>
                                            {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-icon-split btn-sm">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">{{ $formMode === 'edit' ? __('label.update') : __('label.create') }}</span>
                                    </button>
