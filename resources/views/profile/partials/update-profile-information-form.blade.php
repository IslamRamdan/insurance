<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('البيانات الشخصية') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('قم بتحديث بيانات حسابك الشخصية وبريدك الإلكتروني.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- الاسم بالكامل --}}
        <div>
            <x-input-label for="name" :value="__('الاسم بالكامل')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- البريد الإلكتروني --}}
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            {{-- التحقق من تفعيل البريد الإلكتروني --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('عنوان بريدك الإلكتروني غير مفعّل.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('اضغط هنا لإعادة إرسال رابط التفعيل.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('تم إرسال رابط تفعيل جديد إلى عنوان بريدك الإلكتروني.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- اسم الشركة (تمت إضافته ليتوافق مع التعديل الجديد) --}}
        <div>
            <x-input-label for="company_name" :value="__('اسم الشركة (اختياري)')" />
            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full"
                :value="old('company_name', $user->company_name)" autocomplete="organization" />
            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
        </div>

        {{-- رقم الهاتف المنسق بالكامل لـ AdminLTE والهوية السعودية --}}
        <div class="form-group mb-3 mt-4 text-right" dir="rtl">


            <div class="input-group">
                <input type="tel" id="phone_number" name="phone_number"
                    class="form-control @error('phone_number') is-invalid @enderror"
                    value="{{ old('phone_number', $user->phone_number) }}" placeholder="مثال: 05xxxxxxxx" required
                    autocomplete="tel" style="border-radius: 10px; height: 45px; border: 1px solid #d1e7dd;">

                <div class="input-group-append">
                    <div class="input-group-text"
                        style="border-radius: 10px 0 0 10px !important; color: #006C35; background: transparent;">
                        <span class="fas fa-phone"></span>
                    </div>
                </div>

                @error('phone_number')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        {{-- زر الحفظ والتنبيه --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('حفظ البيانات') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 font-weight-bold">{{ __('تم الحفظ بنجاح.') }}</p>
            @endif
        </div>
    </form>
</section>
