<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'offitrade') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


            <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ $siteSettings?->favicon_path ? Storage::url($siteSettings->favicon_path) : asset('favicon.png') }}" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-serif:ui-serif,Georgia,Cambria,"Times New Roman",Times,serif;--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--color-red-50:oklch(.971 .013 17.38);--color-red-100:oklch(.936 .032 17.717);--color-red-200:oklch(.885 .062 18.334);--color-red-300:oklch(.808 .114 19.571);--color-red-400:oklch(.704 .191 22.216);--color-red-500:oklch(.637 .237 25.331);--color-red-600:oklch(.577 .245 27.325);--color-red-700:oklch(.505 .213 27.518);--color-red-800:oklch(.444 .177 26.899);--color-red-900:oklch(.396 .141 25.723);--color-red-950:oklch(.258 .092 26.042);--color-orange-50:oklch(.98 .016 73.684);--color-orange-100:oklch(.954 .038 75.164);--color-orange-200:oklch(.901 .076 70.697);--color-orange-300:oklch(.837 .128 66.29);--color-orange-400:oklch(.75 .183 55.934);--color-orange-500:oklch(.705 .213 47.604);--color-orange-600:oklch(.646 .222 41.116);--color-orange-700:oklch(.553 .195 38.402);--color-orange-800:oklch(.47 .157 37.304);--color-orange-900:oklch(.408 .123 38.172);--color-orange-950:oklch(.266 .079 36.259);--color-amber-50:oklch(.987 .022 95.277);--color-amber-100:oklch(.962 .059 95.617);--color-amber-200:oklch(.924 .12 95.746);--color-amber-300:oklch(.879 .169 91.605);--color-amber-400:oklch(.828 .189 84.429);--color-amber-500:oklch(.769 .188 70.08);--color-amber-600:oklch(.666 .179 58.318);--color-amber-700:oklch(.555 .163 48.998);--color-amber-800:oklch(.473 .137 46.201);--color-amber-900:oklch(.414 .112 45.904);--color-amber-950:oklch(.279 .077 45.635);--color-yellow-50:oklch(.987 .026 102.212);--color-yellow-100:oklch(.973 .071 103.193);--color-yellow-200:oklch(.945 .129 101.54);--color-yellow-300:oklch(.905 .182 98.111);--color-yellow-400:oklch(.852 .199 91.936);--color-[#4f6ba3]:oklch(.795 .184 86.047);--color-yellow-600:oklch(.681 .162 75.834);--color-yellow-700:oklch(.554 .135 66.442);--color-yellow-800:oklch(.476 .114 61.907);--color-yellow-900:oklch(.421 .095 57.708);--color-yellow-950:oklch(.286 .066 53.813);--color-lime-50:oklch(.986 .031 120.757);--color-lime-100:oklch(.967 .067 122.328);--color-lime-200:oklch(.938 .127 124.321);--color-lime-300:oklch(.897 .196 126.665);--color-lime-400:oklch(.841 .238 128.85);--color-lime-500:oklch(.768 .233 130.85);--color-lime-600:oklch(.648 .2 131.684);--color-lime-700:oklch(.532 .157 131.589);--color-lime-800:oklch(.453 .124 130.933);--color-lime-900:oklch(.405 .101 131.063);--color-lime-950:oklch(.274 .072 132.109);--color-green-50:oklch(.982 .018 155.826);--color-green-100:oklch(.962 .044 156.743);--color-green-200:oklch(.925 .084 155.995);--color-green-300:oklch(.871 .15 154.449);--color-green-400:oklch(.792 .209 151.711);--color-green-500:oklch(.723 .219 149.579);--color-green-600:oklch(.627 .194 149.214);--color-green-700:oklch(.527 .154 150.069);--color-green-800:oklch(.448 .119 151.328);--color-green-900:oklch(.393 .095 152.535);--color-green-950:oklch(.266 .065 152.934);--color-emerald-50:oklch(.979 .021 166.113);--color-emerald-100:oklch(.95 .052 163.051);--color-emerald-200:oklch(.905 .093 164.15);--color-emerald-300:oklch(.845 .143 164.978);--color-emerald-400:oklch(.765 .177 163.223);--color-emerald-500:oklch(.696 .17 162.48);--color-emerald-600:oklch(.596 .145 163.225);--color-emerald-700:oklch(.508 .118 165.612);--color-emerald-800:oklch(.432 .095 166.913);--color-emerald-900:oklch(.378 .077 168.94);--color-emerald-950:oklch(.262 .051 172.552);--color-teal-50:oklch(.984 .014 180.72);--color-teal-100:oklch(.953 .051 180.801);--color-teal-200:oklch(.91 .096 180.426);--color-teal-300:oklch(.855 .138 181.071);--color-teal-400:oklch(.777 .152 181.912);--color-teal-500:oklch(.704 .14 182.503);--color-teal-600:oklch(.6 .118 184.704);--color-teal-700:oklch(.511 .096 186.391);--color-teal-800:oklch(.437 .078 188.216);--color-teal-900:oklch(.386 .063 188.416);--color-teal-950:oklch(.277 .046 192.524);--color-cyan-50:oklch(.984 .019 200.873);--color-cyan-100:oklch(.956 .045 203.388);--color-cyan-200:oklch(.917 .08 205.041);--color-cyan-300:oklch(.865 .127 207.078);--color-cyan-400:oklch(.789 .154 211.53);--color-cyan-500:oklch(.715 .143 215.221);--color-cyan-600:oklch(.609 .126 221.723);--color-cyan-700:oklch(.52 .105 223.128);--color-cyan-800:oklch(.45 .085 224.283);--color-cyan-900:oklch(.398 .07 227.392);--color-cyan-950:oklch(.302 .056 229.695);--color-sky-50:oklch(.977 .013 236.62);--color-sky-100:oklch(.951 .026 236.824);--color-sky-200:oklch(.901 .058 230.902);--color-sky-300:oklch(.828 .111 230.318);--color-sky-400:oklch(.746 .16 232.661);--color-sky-500:oklch(.685 .169 237.323);--color-sky-600:oklch(.588 .158 241.966);--color-sky-700:oklch(.5 .134 242.749);--color-sky-800:oklch(.443 .11 240.79);--color-sky-900:oklch(.391 .09 240.876);--color-sky-950:oklch(.293 .066 243.157);--color-blue-50:oklch(.97 .014 254.604);--color-blue-100:oklch(.932 .032 255.585);--color-blue-200:oklch(.882 .059 254.128);--color-blue-300:oklch(.809 .105 251.813);--color-blue-400:oklch(.707 .165 254.624);--color-blue-500:oklch(.623 .214 259.815);--color-blue-600:oklch(.546 .245 262.881);--color-blue-700:oklch(.488 .243 264.376);--color-blue-800:oklch(.424 .199 265.638);--color-blue-900:oklch(.379 .146 265.522);--color-blue-950:oklch(.282 .091 267.935);--color-indigo-50:oklch(.962 .018 272.314);--color-indigo-100:oklch(.93 .034 272.788);--color-indigo-200:oklch(.87 .065 274.039);--color-indigo-300:oklch(.785 .115 274.713);--color-indigo-400:oklch(.673 .182 276.935);--color-indigo-500:oklch(.585 .233 277.117);--color-indigo-600:oklch(.511 .262 276.966);--color-indigo-700:oklch(.457 .24 277.023);--color-indigo-800:oklch(.398 .195 277.366);--color-indigo-900:oklch(.359 .144 278.697);--color-indigo-950:oklch(.257 .09 281.288);--color-violet-50:oklch(.969 .016 293.756);--color-violet-100:oklch(.943 .029 294.588);--color-violet-200:oklch(.894 .057 293.283);--color-violet-300:oklch(.811 .111 293.571);--color-violet-400:oklch(.702 .183 293.541);--color-violet-500:oklch(.606 .25 292.717);--color-violet-600:oklch(.541 .281 293.009);--color-violet-700:oklch(.491 .27 292.581);--color-violet-800:oklch(.432 .232 292.759);--color-violet-900:oklch(.38 .189 293.745);--color-violet-950:oklch(.283 .141 291.089);--color-purple-50:oklch(.977 .014 308.299);--color-purple-100:oklch(.946 .033 307.174);--color-purple-200:oklch(.902 .063 306.703);--color-purple-300:oklch(.827 .119 306.383);--color-purple-400:oklch(.714 .203 305.504);--color-purple-500:oklch(.627 .265 303.9);--color-purple-600:oklch(.558 .288 302.321);--color-purple-700:oklch(.496 .265 301.924);--color-purple-800:oklch(.438 .218 303.724);--color-purple-900:oklch(.381 .176 304.987);--color-purple-950:oklch(.291 .149 302.717);--color-fuchsia-50:oklch(.977 .017 320.058);--color-fuchsia-100:oklch(.952 .037 318.852);--color-fuchsia-200:oklch(.903 .076 319.62);--color-fuchsia-300:oklch(.833 .145 321.434);--color-fuchsia-400:oklch(.74 .238 322.16);--color-fuchsia-500:oklch(.667 .295 322.15);--color-fuchsia-600:oklch(.591 .293 322.896);--color-fuchsia-700:oklch(.518 .253 323.949);--color-fuchsia-800:oklch(.452 .211 324.591);--color-fuchsia-900:oklch(.401 .17 325.612);--color-fuchsia-950:oklch(.293 .136 325.661);--color-pink-50:oklch(.971 .014 343.198);--color-pink-100:oklch(.948 .028 342.258);--color-pink-200:oklch(.899 .061 343.231);--color-pink-300:oklch(.823 .12 346.018);--color-pink-400:oklch(.718 .202 349.761);--color-pink-500:oklch(.656 .241 354.308);--color-pink-600:oklch(.592 .249 .584);--color-pink-700:oklch(.525 .223 3.958);--color-pink-800:oklch(.459 .187 3.815);--color-pink-900:oklch(.408 .153 2.432);--color-pink-950:oklch(.284 .109 3.907);--color-rose-50:oklch(.969 .015 12.422);--color-rose-100:oklch(.941 .03 12.58);--color-rose-200:oklch(.892 .058 10.001);--color-rose-300:oklch(.81 .117 11.638);--color-rose-400:oklch(.712 .194 13.428);--color-rose-500:oklch(.645 .246 16.439);--color-rose-600:oklch(.586 .253 17.585);--color-rose-700:oklch(.514 .222 16.935);--color-rose-800:oklch(.455 .188 13.697);--color-rose-900:oklch(.41 .159 10.272);--color-rose-950:oklch(.271 .105 12.094);--color-slate-50:oklch(.984 .003 247.858);--color-slate-100:oklch(.968 .007 247.896);--color-slate-200:oklch(.929 .013 255.508);--color-slate-300:oklch(.869 .022 252.894);--color-slate-400:oklch(.704 .04 256.788);--color-slate-500:oklch(.554 .046 257.417);--color-slate-600:oklch(.446 .043 257.281);--color-slate-700:oklch(.372 .044 257.287);--color-slate-800:oklch(.279 .041 260.031);--color-slate-900:oklch(.208 .042 265.755);--color-slate-950:oklch(.129 .042 264.695);--color-gray-50:oklch(.985 .002 247.839);--color-gray-100:oklch(.967 .003 264.542);--color-gray-200:oklch(.928 .006 264.531);--color-whit:oklch(.872 .01 258.338);--color-gray-400:oklch(.707 .022 261.325);--color-gray-500:oklch(.551 .027 264.364);--color-gray-600:oklch(.446 .03 256.802);--color-gray-700:oklch(.373 .034 259.733);--color-gray-800:oklch(.278 .033 256.848);--color-gray-900:oklch(.21 .034 264.665);--color-gray-950:oklch(.13 .028 261.692);--color-zinc-50:oklch(.985 0 0);--color-zinc-100:oklch(.967 .001 286.375);--color-zinc-200:oklch(.92 .004 286.32);--color-zinc-300:oklch(.871 .006 286.286);--color-zinc-400:oklch(.705 .015 286.067);--color-zinc-500:oklch(.552 .016 285.938);--color-zinc-600:oklch(.442 .017 285.786);--color-zinc-700:oklch(.37 .013 285.805);--color-zinc-800:oklch(.274 .006 286.033);--color-zinc-900:oklch(.21 .006 285.885);--color-zinc-950:oklch(.141 .005 285.823);--color-neutral-50:oklch(.985 0 0);--color-neutral-100:oklch(.97 0 0);--color-neutral-200:oklch(.922 0 0);--color-neutral-300:oklch(.87 0 0);--color-neutral-400:oklch(.708 0 0);--color-neutral-500:oklch(.556 0 0);--color-neutral-600:oklch(.439 0 0);--color-neutral-700:oklch(.371 0 0);--color-neutral-800:oklch(.269 0 0);--color-neutral-900:oklch(.205 0 0);--color-neutral-950:oklch(.145 0 0);--color-stone-50:oklch(.985 .001 106.423);--color-stone-100:oklch(.97 .001 106.424);--color-stone-200:oklch(.923 .003 48.717);--color-stone-300:oklch(.869 .005 56.366);--color-stone-400:oklch(.709 .01 56.259);--color-stone-500:oklch(.553 .013 58.071);--color-stone-600:oklch(.444 .011 73.639);--color-stone-700:oklch(.374 .01 67.558);--color-stone-800:oklch(.268 .007 34.298);--color-stone-900:oklch(.216 .006 56.043);--color-stone-950:oklch(.147 .004 49.25);--color-black:#000;--color-white:#fff;--spacing:.25rem;--breakpoint-sm:40rem;--breakpoint-md:48rem;--breakpoint-lg:64rem;--breakpoint-xl:80rem;--breakpoint-2xl:96rem;--container-3xs:16rem;--container-2xs:18rem;--container-xs:20rem;--container-sm:24rem;--container-md:28rem;--container-lg:32rem;--container-xl:36rem;--container-2xl:42rem;--container-3xl:48rem;--container-4xl:56rem;--container-5xl:64rem;--container-6xl:72rem;--container-7xl:80rem;--text-xs:.75rem;--text-xs--line-height:calc(1/.75);--text-sm:.875rem;--text-sm--line-height:calc(1.25/.875);--text-base:1rem;--text-base--line-height: 1.5 ;--text-lg:1.125rem;--text-lg--line-height:calc(1.75/1.125);--text-xl:1.25rem;--text-xl--line-height:calc(1.75/1.25);--text-2xl:1.5rem;--text-2xl--line-height:calc(2/1.5);--text-3xl:1.875rem;--text-3xl--line-height: 1.2 ;--text-4xl:2.25rem;--text-4xl--line-height:calc(2.5/2.25);--text-5xl:3rem;--text-5xl--line-height:1;--text-6xl:3.75rem;--text-6xl--line-height:1;--text-7xl:4.5rem;--text-7xl--line-height:1;--text-8xl:6rem;--text-8xl--line-height:1;--text-9xl:8rem;--text-9xl--line-height:1;--font-weight-thin:100;--font-weight-extralight:200;--font-weight-light:300;--font-weight-normal:400;--font-weight-medium:500;--font-weight-semibold:600;--font-weight-bold:700;--font-weight-extrabold:800;--font-weight-black:900;--tracking-tighter:-.05em;--tracking-tight:-.025em;--tracking-normal:0em;--tracking-wide:.025em;--tracking-wider:.05em;--tracking-widest:.1em;--leading-tight:1.25;--leading-snug:1.375;--leading-normal:1.5;--leading-relaxed:1.625;--leading-loose:2;--radius-xs:.125rem;--radius-sm:.25rem;--radius-md:.375rem;--radius-lg:.5rem;--radius-xl:.75rem;--radius-2xl:1rem;--radius-3xl:1.5rem;--radius-4xl:2rem;--shadow-2xs:0 1px #0000000d;--shadow-xs:0 1px 2px 0 #0000000d;--shadow-sm:0 1px 3px 0 #0000001a,0 1px 2px -1px #0000001a;--:0 4px 6px -1px #0000001a,0 2px 4px -2px #0000001a;--shadow-lg:0 10px 15px -3px #0000001a,0 4px 6px -4px #0000001a;--shadow-xl:0 20px 25px -5px #0000001a,0 8px 10px -6px #0000001a;--shadow-2xl:0 25px 50px -12px #00000040;--inset-shadow-2xs:inset 0 1px #0000000d;--inset-shadow-xs:inset 0 1px 1px #0000000d;--inset-shadow-sm:inset 0 2px 4px #0000000d;--drop-shadow-xs:0 1px 1px #0000000d;--drop-shadow-sm:0 1px 2px #00000026;--drop-:0 3px 3px #0000001f;--drop-shadow-lg:0 4px 4px #00000026;--drop-shadow-xl:0 9px 7px #0000001a;--drop-shadow-2xl:0 25px 25px #00000026;--ease-in:cubic-bezier(.4,0,1,1);--ease-out:cubic-bezier(0,0,.2,1);--ease-in-out:cubic-bezier(.4,0,.2,1);--animate-spin:spin 1s linear infinite;--animate-ping:ping 1s cubic-bezier(0,0,.2,1)infinite;--animate-pulse:pulse 2s cubic-bezier(.4,0,.6,1)infinite;--animate-bounce:bounce 1s infinite;--blur-xs:4px;--blur-sm:8px;--blur-md:12px;--blur-lg:16px;--blur-xl:24px;--blur-2xl:40px;--blur-3xl:64px;--perspective-dramatic:100px;--perspective-near:300px;--perspective-normal:500px;--perspective-midrange:800px;--perspective-distant:1200px;--aspect-video:16/9;--default-transition-duration:.15s;--default-transition-timing-function:cubic-bezier(.4,0,.2,1);--default-font-family:var(--font-sans);--default-font-feature-settings:var(--font-sans--font-feature-settings);--default-font-variation-settings:var(--font-sans--font-variation-settings);--default-mono-font-family:var(--font-mono);--default-mono-font-feature-settings:var(--font-mono--font-feature-settings);--default-mono-font-variation-settings:var(--font-mono--font-variation-settings)}}@layer base{*,:after,:before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}::file-selector-button{box-sizing:border-box;border:0 solid;margin:0;padding:0}html,:host{-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;line-height:1.5;font-family:var(--default-font-family,ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji");font-feature-settings:var(--default-font-feature-settings,normal);font-variation-settings:var(--default-font-variation-settings,normal);-webkit-tap-highlight-color:transparent}body{line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;-webkit-text-decoration:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,samp,pre{font-family:var(--default-mono-font-family,ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace);font-feature-settings:var(--default-mono-font-feature-settings,normal);font-variation-settings:var(--default-mono-font-variation-settings,normal);font-size:1em}small{font-size:80%}sub,sup{vertical-align:baseline;font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}:-moz-focusring{outline:auto}progress{vertical-align:baseline}summary{display:list-item}ol,ul,menu{list-style:none}img,svg,video,canvas,audio,iframe,embed,object{vertical-align:middle;display:block}img,video{max-width:100%;height:auto}button,input,select,optgroup,textarea{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}::file-selector-button{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}:where(select:is([multiple],[size])) optgroup{font-weight:bolder}:where(select:is([multiple],[size])) optgroup option{padding-inline-start:20px}::file-selector-button{margin-inline-end:4px}::placeholder{opacity:1;color:color-mix(in oklab,currentColor 50%,transparent)}textarea{resize:vertical}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-date-and-time-value{min-height:1lh;text-align:inherit}::-webkit-datetime-edit{display:inline-flex}::-webkit-datetime-edit-fields-wrapper{padding:0}::-webkit-datetime-edit{padding-block:0}::-webkit-datetime-edit-year-field{padding-block:0}::-webkit-datetime-edit-month-field{padding-block:0}::-webkit-datetime-edit-day-field{padding-block:0}::-webkit-datetime-edit-hour-field{padding-block:0}::-webkit-datetime-edit-minute-field{padding-block:0}::-webkit-datetime-edit-second-field{padding-block:0}::-webkit-datetime-edit-millisecond-field{padding-block:0}::-webkit-datetime-edit-meridiem-field{padding-block:0}:-moz-ui-invalid{box-shadow:none}button,input:where([type=button],[type=reset],[type=submit]){-webkit-appearance:button;-moz-appearance:button;appearance:button}::file-selector-button{-webkit-appearance:button;-moz-appearance:button;appearance:button}::-webkit-inner-spin-button{height:auto}::-webkit-outer-spin-button{height:auto}[hidden]:where(:not([hidden=until-found])){display:none!important}}@layer components;@layer utilities{.absolute{position:absolute}.relative{position:relative}.static{position:static}.inset-0{inset:calc(var(--spacing)*0)}.-mt-\[4\.9rem\]{margin-top:-4.9rem}.-mb-px{margin-bottom:-1px}.mb-1{margin-bottom:calc(var(--spacing)*1)}.mb-2{margin-bottom:calc(var(--spacing)*2)}.mb-4{margin-bottom:calc(var(--spacing)*4)}.mb-6{margin-bottom:calc(var(--spacing)*6)}.-
ml-8{margin-left:calc(var(--spacing)*-8)}.flex{display:flex}.hidden{display:none}.inline-block{display:inline-block}.inline-flex{display:inline-flex}.table{display:table}.aspect-\[335\/376\]{aspect-ratio:335/376}.h-1{height:calc(var(--spacing)*1)}.h-1\.5{height:calc(var(--spacing)*1.5)}.h-2{height:calc(var(--spacing)*2)}.h-2\.5{height:calc(var(--spacing)*2.5)}.h-3{height:calc(var(--spacing)*3)}.h-3\.5{height:calc(var(--spacing)*3.5)}.h-14{height:calc(var(--spacing)*14)}.h-14\.5{height:calc(var(--spacing)*14.5)}.min-h-screen{min-height:100vh}.w-1{width:calc(var(--spacing)*1)}.w-1\.5{width:calc(var(--spacing)*1.5)}.w-2{width:calc(var(--spacing)*2)}.w-2\.5{width:calc(var(--spacing)*2.5)}.w-3{width:calc(var(--spacing)*3)}.w-3\.5{width:calc(var(--spacing)*3.5)}.w-\[448px\]{width:448px}.w-full{width:100%}.max-w-\[335px\]{max-width:335px}.max-w-none{max-width:none}.flex-1{flex:1}.shrink-0{flex-shrink:0}.translate-y-0{--tw-translate-y:calc(var(--spacing)*0);translate:var(--tw-translate-x)var(--tw-translate-y)}.transform{transform:var(--tw-rotate-x)var(--tw-rotate-y)var(--tw-rotate-z)var(--tw-skew-x)var(--tw-skew-y)}.flex-col{flex-direction:column}.flex-col-reverse{flex-direction:column-reverse}.items-center{align-items:center}.justify-center{justify-content:center}.justify-end{justify-content:flex-end}.gap-3{gap:calc(var(--spacing)*3)}.gap-4{gap:calc(var(--spacing)*4)}:where(.space-x-1>:not(:last-child)){--tw-space-x-reverse:0;margin-inline-start:calc(calc(var(--spacing)*1)*var(--tw-space-x-reverse));margin-inline-end:calc(calc(var(--spacing)*1)*calc(1 - var(--tw-space-x-reverse)))}.overflow-hidden{overflow:hidden}.rounded-full{border-radius:3.40282e38px}.rounded-sm{border-radius:var(--radius-sm)}.rounded-t-lg{border-top-left-radius:var(--radius-lg);border-top-right-radius:var(--radius-lg)}.rounded-br-lg{border-bottom-right-radius:var(--radius-lg)}.rounded-bl-lg{border-bottom-left-radius:var(--radius-lg)}.border{border-style:var(--tw-border-style);border-width:1px}.border-\[\#19140035\]{border-color:#19140035}.border-\[\#e3e3e0\]{border-color:#e3e3e0}.border-black{border-color:var(--color-black)}.border-transparent{border-color:#0000}.bg-\[\#1b1b18\]{background-color:#1b1b18}.bg-\[\#FDFDFC\]{background-color:#fdfdfc}.bg-\[\#dbdbd7\]{background-color:#dbdbd7}.bg-\[\#fff2f2\]{background-color:#fff2f2}.bg-white{background-color:var(--color-white)}.p-6{padding:calc(var(--spacing)*6)}.px-5{padding-inline:calc(var(--spacing)*5)}.py-1{padding-block:calc(var(--spacing)*1)}.py-1\.5{padding-block:calc(var(--spacing)*1.5)}.py-2{padding-block:calc(var(--spacing)*2)}.pb-12{padding-bottom:calc(var(--spacing)*12)}.text-sm{font-size:var(--text-sm);line-height:var(--tw-leading,var(--text-sm--line-height))}.text-\[13px\]{font-size:13px}.leading-\[20px\]{--tw-leading:20px;line-height:20px}.leading-normal{--tw-leading:var(--leading-normal);line-height:var(--leading-normal)}.font-medium{--tw-font-weight:var(--font-weight-medium);font-weight:var(--font-weight-medium)}.text-\[\#1b1b18\]{color:#1b1b18}.text-\[\#706f6c\]{color:#706f6c}.text-\[\#F53003\],.text-\[\#f53003\]{color:#f53003}.text-white{color:var(--color-white)}.underline{text-decoration-line:underline}.underline-offset-4{text-underline-offset:4px}.opacity-100{opacity:1}.shadow-\[0px_0px_1px_0px_rgba\(0\,0\,0\,0\.03\)\,0px_1px_2px_0px_rgba\(0\,0\,0\,0\.06\)\]{--tw-shadow:0px 0px 1px 0px var(--tw-shadow-color,#00000008),0px 1px 2px 0px var(--tw-shadow-color,#0000000f);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.shadow-\[inset_0px_0px_0px_1px_rgba\(26\,26\,0\,0\.16\)\]{--tw-shadow:inset 0px 0px 0px 1px var(--tw-shadow-color,#1a1a0029);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.\!filter{filter:var(--tw-blur,)var(--tw-brightness,)var(--tw-contrast,)var(--tw-grayscale,)var(--tw-hue-rotate,)var(--tw-invert,)var(--tw-saturate,)var(--tw-sepia,)var(--tw-drop-shadow,)!important}.filter{filter:var(--tw-blur,)var(--tw-brightness,)var(--tw-contrast,)var(--tw-grayscale,)var(--tw-hue-rotate,)var(--tw-invert,)var(--tw-saturate,)var(--tw-sepia,)var(--tw-drop-shadow,)}.transition-all{transition-property:all;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function));transition-duration:var(--tw-duration,var(--default-transition-duration))}.transition-opacity{transition-property:opacity;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function));transition-duration:var(--tw-duration,var(--default-transition-duration))}.delay-300{transition-delay:.3s}.duration-750{--tw-duration:.75s;transition-duration:.75s}.not-has-\[nav\]\:hidden:not(:has(:is(nav))){display:none}.before\:absolute:before{content:var(--tw-content);position:absolute}.before\:top-0:before{content:var(--tw-content);top:calc(var(--spacing)*0)}.before\:top-1\/2:before{content:var(--tw-content);top:50%}.before\:bottom-0:before{content:var(--tw-content);bottom:calc(var(--spacing)*0)}.before\:bottom-1\/2:before{content:var(--tw-content);bottom:50%}.before\:left-\[0\.4rem\]:before{content:var(--tw-content);left:.4rem}.before\:border-l:before{content:var(--tw-content);border-left-style:var(--tw-border-style);border-left-width:1px}.before\:border-\[\#e3e3e0\]:before{content:var(--tw-content);border-color:#e3e3e0}@media (hover:hover){.hover\:border-\[\#1915014a\]:hover{border-color:#1915014a}.hover\:border-\[\#19140035\]:hover{border-color:#19140035}.hover\:border-black:hover{border-color:var(--color-black)}.hover\:bg-black:hover{background-color:var(--color-black)}}@media (width>=64rem){.lg\:-mt-\[6\.6rem\]{margin-top:-6.6rem}.lg\:mb-0{margin-bottom:calc(var(--spacing)*0)}.lg\:mb-6{margin-bottom:calc(var(--spacing)*6)}.lg\:-ml-px{margin-left:-1px}.lg\:ml-0{margin-left:calc(var(--spacing)*0)}.lg\:block{display:block}.lg\:aspect-auto{aspect-ratio:auto}.lg\:w-\[438px\]{width:438px}.lg\:max-w-4xl{max-width:var(--container-4xl)}.lg\:grow{flex-grow:1}.lg\:flex-row{flex-direction:row}.lg\:justify-center{justify-content:center}.lg\:rounded-t-none{border-top-left-radius:0;border-top-right-radius:0}.lg\:rounded-tl-lg{border-top-left-radius:var(--radius-lg)}.lg\:rounded-r-lg{border-top-right-radius:var(--radius-lg);border-bottom-right-radius:var(--radius-lg)}.lg\:rounded-br-none{border-bottom-right-radius:0}.lg\:p-8{padding:calc(var(--spacing)*8)}.lg\:p-20{padding:calc(var(--spacing)*20)}}@media (prefers-color-scheme:dark){.dark\:block{display:block}.dark\:hidden{display:none}.dark\:border-\[\#3E3E3A\]{border-color:#3e3e3a}.dark\:border-\[\#eeeeec\]{border-color:#eeeeec}.dark\:bg-\[\#0a0a0a\]{background-color:#0a0a0a}.dark\:bg-\[\#1D0002\]{background-color:#1d0002}.dark\:bg-\[\#3E3E3A\]{background-color:#3e3e3a}.dark\:bg-\[\#161615\]{background-color:#161615}.dark\:bg-\[\#eeeeec\]{background-color:#eeeeec}.dark\:text-\[\#1C1C1A\]{color:#1c1c1a}.dark\:text-\[\#A1A09A\]{color:#a1a09a}.dark\:text-\[\#EDEDEC\]{color:#ededec}.dark\:text-\[\#F61500\]{color:#f61500}.dark\:text-\[\#FF4433\]{color:#f43}.dark\:shadow-\[inset_0px_0px_0px_1px_\#fffaed2d\]{--tw-shadow:inset 0px 0px 0px 1px var(--tw-shadow-color,#fffaed2d);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.dark\:before\:border-\[\#3E3E3A\]:before{content:var(--tw-content);border-color:#3e3e3a}@media (hover:hover){.dark\:hover\:border-\[\#3E3E3A\]:hover{border-color:#3e3e3a}.dark\:hover\:border-\[\#62605b\]:hover{border-color:#62605b}.dark\:hover\:border-white:hover{border-color:var(--color-white)}.dark\:hover\:bg-white:hover{background-color:var(--color-white)}}}@starting-style{.starting\:translate-y-4{--tw-translate-y:calc(var(--spacing)*4);translate:var(--tw-translate-x)var(--tw-translate-y)}}@starting-style{.starting\:translate-y-6{--tw-translate-y:calc(var(--spacing)*6);translate:var(--tw-translate-x)var(--tw-translate-y)}}@starting-style{.starting\:opacity-0{opacity:0}}}@keyframes spin{to{transform:rotate(360deg)}}@keyframes ping{75%,to{opacity:0;transform:scale(2)}}@keyframes pulse{50%{opacity:.5}}@keyframes bounce{0%,to{animation-timing-function:cubic-bezier(.8,0,1,1);transform:translateY(-25%)}50%{animation-timing-function:cubic-bezier(0,0,.2,1);transform:none}}@property --tw-translate-x{syntax:"*";inherits:false;initial-value:0}@property --tw-translate-y{syntax:"*";inherits:false;initial-value:0}@property --tw-translate-z{syntax:"*";inherits:false;initial-value:0}@property --tw-rotate-x{syntax:"*";inherits:false;initial-value:rotateX(0)}@property --tw-rotate-y{syntax:"*";inherits:false;initial-value:rotateY(0)}@property --tw-rotate-z{syntax:"*";inherits:false;initial-value:rotateZ(0)}@property --tw-skew-x{syntax:"*";inherits:false;initial-value:skewX(0)}@property --tw-skew-y{syntax:"*";inherits:false;initial-value:skewY(0)}@property --tw-space-x-reverse{syntax:"*";inherits:false;initial-value:0}@property --tw-border-style{syntax:"*";inherits:false;initial-value:solid}@property --tw-leading{syntax:"*";inherits:false}@property --tw-font-weight{syntax:"*";inherits:false}@property --tw-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-shadow-color{syntax:"*";inherits:false}@property --tw-inset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-shadow-color{syntax:"*";inherits:false}@property --tw-ring-color{syntax:"*";inherits:false}@property --tw-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-ring-color{syntax:"*";inherits:false}@property --tw-inset-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-ring-inset{syntax:"*";inherits:false}@property --tw-ring-offset-width{syntax:"<length>";inherits:false;initial-value:0}@property --tw-ring-offset-color{syntax:"*";inherits:false;initial-value:#fff}@property --tw-ring-offset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-blur{syntax:"*";inherits:false}@property --tw-brightness{syntax:"*";inherits:false}@property --tw-contrast{syntax:"*";inherits:false}@property --tw-grayscale{syntax:"*";inherits:false}@property --tw-hue-rotate{syntax:"*";inherits:false}@property --tw-invert{syntax:"*";inherits:false}@property --tw-opacity{syntax:"*";inherits:false}@property --tw-saturate{syntax:"*";inherits:false}@property --tw-sepia{syntax:"*";inherits:false}@property --tw-drop-shadow{syntax:"*";inherits:false}@property --tw-duration{syntax:"*";inherits:false}@property --tw-content{syntax:"*";inherits:false;initial-value:""}
            </style>
        @endif
    </head>
  <body class="bg-white text-gray-900 dark:text-gray-100 font-sans dark:bg-gray-900 flex flex-col min-h-screen">
    

    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Main Content -->
    <main class="flex-grow">
            <!-- Section Hero -->
            <section id="accueil" class="relative min-h-screen flex items-center justify-center overflow-hidden text-white">
              <!-- Video Background -->
              <video autoplay  loop playsinline muted preload="auto"
                class="absolute inset-0 w-full h-full object-cover -z-10 brightness-75 saturate-125">
                <source src="{{ $bgVideoSrc }}" type="video/mp4" />
                {{ __('site.hero.video_not_supported') }}

              </video>

              <!-- Overlay foncé -->
              <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/80 z-10"></div>

              <!-- Contenu Hero -->
              <div class="relative z-10 text-center px-4 sm:px-6 md:px-8 max-w-4xl animate-fadein">
                <h2 class="text-2xl md:text-4xl font-extrabold mb-10 leading-tight drop-shadow-lg">{!! __('site.hero.title') !!}</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6">
                  <a href="#contact"
                    class="inline-block bg-white text-[#1b2336] font-bold py-4 px-10 md:mb-8 rounded-full shadow-lg 
                            hover:bg-gray-200 hover:scale-105 transition-all duration-300">
                    {{ __('site.hero.cta_appointment') }}
                  </a>
                  <a href="/noservices" 
                    class="inline-block bg-[#4f6ba3] text-white font-bold py-4 px-10 md:mb-8 rounded-full shadow-lg 
                            hover:bg-[#324d78] hover:scale-105 transition-all duration-300 ">
                   {{ __('site.services.cta_discover') }}
                  </a>
                </div>
              </div>
            </section>


            <!-- Section About flottante --> 
            <section id="about" class="relative z-20 md:z-20 sm:z-20 lg:z-20 -mt-32 dark:bg-gray-900 ">
              <div class="bg-white rounded-t-3xl shadow-xl w-full md:w-12/12 lg:max-w-full mx-auto px-6 md:px-12 py-16
                          transform transition-all duration-700 hover:scale-[1.01] hover:shadow-2xl
                          dark:bg-gray-900 ">
                
                <!-- Header -->
                <div class="text-center mb-12">
                  <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl font-extrabold text-[#4f6ba3] dark:text-blue-400 relative inline-block">
                    {{ __('site.about.title') }}
                    <span class="block w-40 h-1.5 bg-gradient-to-r from-[#4f6ba3] to-transparent dark:bg-gradient-to-r dark:from-blue-400 dark:to-transparent mx-auto mt-3 rounded-full"></span>
                  </h1>

                </div>

                <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                  
                  <!-- Texte -->
                  <div class="lg:w-6/12 lg:pl-12 animate-fadeinright">
                    <h3 class="text-3xl font-bold text-[#4f6ba3] dark:text-blue-400 mb-6 leading-snug">
                      {!! __('site.about.subtitle_html') !!}
                    </h3>
                    
                    <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed mb-6">
                      {!! __('site.about.p1') !!}
                    </p>
                    
                    
                    @php
                      $aboutQuote = trim(__('site.about.quote'));
                      // Replace normal spaces around French guillemets with non-breaking spaces
                      $aboutQuote = str_replace(['« ', ' »'], ['«&nbsp;', '&nbsp;»'], $aboutQuote);
                      // Collapse multiple whitespace and remove hard line breaks that break layout
                      $aboutQuote = preg_replace('/[\r\n\t]+/', ' ', $aboutQuote);
                      $aboutQuote = preg_replace('/\s{2,}/', ' ', $aboutQuote);
                    @endphp

                    <blockquote class="border-l-4 border-[#4f6ba3] pl-4 italic text-gray-600 dark:text-gray-300 text-base sm:text-lg bg-gray-50 dark:bg-gray-800 py-2 px-5 rounded-md shadow-sm ">
                      <p class="whitespace-normal break-words leading-relaxed max-w-prose">
                        {!! $aboutQuote !!}
                      </p>
                    </blockquote>

                    <!-- Stats supprimées temporairement -->
                  </div>

                  <!-- Images avec superposition dynamique -->
                  <div class="lg:w-6/12 relative group animate-fadeinleft">
                    <div class="relative w-full max-w-lg mx-auto">
                      <img src="/images/img1.jpg" alt="Bureau moderne" loading="lazy" decoding="async"
                          class="w-4/5 rounded-2xl shadow-2xl transform rotate-[-3deg] translate-x-6 translate-y-4 
                                  transition-all duration-700 group-hover:scale-105 group-hover:rotate-0">
                      <img src="/images/img2.jpg" alt="Équipe au travail" loading="lazy" decoding="async"
                          class="absolute top-6 right-0 w-4/5 rounded-2xl shadow-2xl transform rotate-3 
                                  transition-all duration-700 group-hover:scale-105 group-hover:rotate-0">
                    </div>
                  </div>

                </div>
              </div>
            </section>



     
            <!--  Services Section -->
            <section id="services" class="relative py-12 lg:py-16 overflow-hidden bg-gradient-to-br from-[#4f6ba3] to-[#283043] dark:from-gray-900 dark:to-gray-800">
                  
                  <!-- Abstract Animated Background -->
                  <div class="absolute inset-0 overflow-hidden pointer-events-none">
                      <!-- Effets de fond -->
                      <div class="hidden md:block absolute -top-16 -left-16 w-72 h-72 bg-white/10 rounded-full blur-2xl opacity-30"></div>
                      <div class="hidden md:block absolute -bottom-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl opacity-20"></div>

                      <!-- Blobs animés -->
                      <div class="absolute top-0 -left-8 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay animate-blob"></div>
                      <div class="absolute right-16 -bottom-2 w-48 h-48 bg-white/10 rounded-full mix-blend-overlay animate-blob animation-delay-2000"></div>
                      <div class="absolute  right-16 -bottom-16 w-80 h-80 bg-white/10 rounded-full mix-blend-overlay animate-blob animation-delay-4000"></div>
                  </div>

                  <div class="w-full max-w-[95%] mx-auto px-4 md:px-8 relative z-10">
                    
                    <!-- Full Width Title Section -->
                    <div class="w-full mb-10">
                         <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight mb-4 drop-shadow-md">
                            {{ __('site.services.title') }}
                         </h1>
                         <div class="w-40 h-1.5 bg-gradient-to-r from-blue-300 to-transparent rounded-full dark:bg-gradient-to-r dark:from-blue-400 dark:to-transparent"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-[2.5fr_1fr] gap-2 md:gap-8 lg:gap-12 items-start">
                              
                              <!-- Content Text (Left) -->
                              <div class="order-1 lg:order-1 animate-fade-in-left">
                                <div class="lg:mb-0">
                                  
                                  <div class="text-base md:text-lg text-blue-50/90 ">
                                      {!! __('site.services.desc') !!}
                                  </div>

                                  <!-- Benefits Grid -->
                                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8 mt-6">
                                      <!-- Card 1 -->
                                      <div class="group bg-white/5 backdrop-blur-md rounded-2xl p-5 border-l-4 border-[#4f6ba3] hover:bg-white/10 hover:border-white/30 transition-all duration-300 hover:-translate-y-1 shadow-lg">
                                        <div class="flex items-start gap-4">
                                          <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-white/20 to-white/5 flex items-center justify-center text-white shadow-inner group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                          </div>
                                          <div>
                                            <h3 class="text-lg font-bold text-white mb-1">{{ __('site.services.adv1_title') }}</h3>
                                            <p class="text-sm text-blue-100/70 leading-snug">{{ __('site.services.adv1_desc') }}</p>
                                          </div>
                                        </div>
                                      </div>
                                      
                                      <!-- Card 2 -->
                                      <div class="group bg-white/5 backdrop-blur-md rounded-2xl p-5 border-l-4 border-[#4f6ba3]  hover:bg-white/10 hover:border-white/30 transition-all duration-300 hover:-translate-y-1 shadow-lg">
                                        <div class="flex items-start gap-4">
                                          <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-white/20 to-white/5 flex items-center justify-center text-white shadow-inner group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" /></svg>
                                          </div>
                                          <div>
                                            <h3 class="text-lg font-bold text-white mb-1">{{ __('site.services.adv2_title') }}</h3>
                                            <p class="text-sm text-blue-100/70 leading-snug">{{ __('site.services.adv2_desc') }}</p>
                                          </div>
                                        </div>
                                      </div>

                                      <!-- Card 3 -->
                                      <div class="group bg-white/5 backdrop-blur-md rounded-2xl p-5 border-l-4 border-[#4f6ba3]  hover:bg-white/10 hover:border-white/30 transition-all duration-300 hover:-translate-y-1 shadow-lg">
                                        <div class="flex items-start gap-4">
                                          <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-white/20 to-white/5 flex items-center justify-center text-white shadow-inner group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                          </div>
                                          <div>
                                            <h3 class="text-lg font-bold text-white mb-1">{{ __('site.services.adv3_title') }}</h3>
                                            <p class="text-sm text-blue-100/70 leading-snug">{{ __('site.services.adv3_desc') }}</p>
                                          </div>
                                        </div>
                                      </div>

                                      <!-- Card 4 -->
                                      <div class="group bg-white/5 backdrop-blur-md rounded-2xl p-5 border-l-4 border-[#4f6ba3]  hover:bg-white/10 hover:border-white/30 transition-all duration-300 hover:-translate-y-1 shadow-lg">
                                        <div class="flex items-start gap-4">
                                          <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-white/20 to-white/5 flex items-center justify-center text-white shadow-inner group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.563.563 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                          </div>
                                          <div>
                                            <h3 class="text-lg font-bold text-white mb-1">{{ __('site.services.adv4_title') }}</h3>
                                            <p class="text-sm text-blue-100/70 leading-snug">{{ __('site.services.adv4_desc') }}</p>
                                          </div>
                                        </div>
                                      </div>
                                  </div>

                                  <!-- CTA Button (Desktop) -->
                                  <div class="hidden lg:block">
                                    <a href="/noservices" class="group inline-flex items-center gap-3  bg-[#4f6ba3] text-white px-8 py-4 rounded-full font-bold shadow-xl hover:shadow-2xl  hover:bg-[#324d78] hover:scale-105  transition-all duration-300 transform hover:-translate-y-1">
                                      {{ __('site.services.cta_discover') }}
                                      <span class="w-8 h-8 rounded-full bg-white text-[#4f6ba3]  flex items-center justify-center group-hover:bg-[#283043] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                      </span>
                                    </a>
                                  </div>
                                </div>
                              </div>
                              
                              <!-- Video Container (Right on Desktop) -->
                              <div class="order-2 lg:order-2 flex justify-center lg:justify-start lg:pl-12 items-start relative perspective-1000">
                                  
                                  <!-- Decorative Glow -->
                                  <div class="absolute inset-0 bg-blue-500/20 blur-[60px] rounded-full animate-pulse-slow pointer-events-none"></div>
                                  
                                  <div class="relative w-full max-w-[240px] sm:max-w-[270px] md:max-w-[300px] lg:max-w-[340px] aspect-[9/16] rounded-[2rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden border-4 border-white/10 backdrop-blur-sm transform transition-all duration-500 hover:scale-[1.02] bg-black/20">
                                      
                                      <!-- Video -->
                                      <video id="servicesVideo" 
                                            class="absolute inset-0 w-full h-full object-cover z-0"
                                            src="{{ $presentationVideoSrc }}" 
                                            preload="metadata"
                                            autoplay 
                                            loop 
                                            playsinline>
                                      </video>
                                      
                                      <!-- Gradient Overlay for controls visibility -->
                                      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent pointer-events-none z-10 opacity-80"></div>

                                      <!-- Custom Video Controls -->
                                      <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[90%] flex items-center gap-3 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl p-3 shadow-lg z-20">
                                          
                                          <!-- Play / Pause Button -->
                                          <button id="videoPlayPause" 
                                                  type="button" 
                                                  aria-label="{{ __('site.aria.play_pause') }}"
                                                  class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors duration-200 shrink-0 text-white">
                                              <svg id="iconPlay" class="w-5 h-5 ml-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7L8 5z"/></svg>
                                              <svg id="iconPause" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>
                                          </button>

                                          <!-- Mute / Unmute Button -->
                                          <button id="videoMute" 
                                                  type="button" 
                                                  aria-label="{{ __('site.aria.mute_unmute') }}"
                                                  class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors duration-200 shrink-0 text-white">
                                              <svg id="iconVolume" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                                  <path d="M5 9v6h4l5 4V5L9 9H5z"/>
                                                  <path d="M16.5 8.5a5 5 0 010 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                              </svg>
                                              <svg id="iconMuted" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="currentColor">
                                                  <path d="M5 9v6h4l5 4V5L9 9H5z"/>
                                                  <path d="M19 9l-6 6M13 9l6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                              </svg>
                                          </button>

                                          <!-- Volume Slider -->
                                          <div class="flex items-center justify-center flex-1 min-w-0 px-2">
                                              <input id="videoVolume" 
                                                    type="range" 
                                                    min="0" 
                                                    max="100" 
                                                    step="5" 
                                                    value="50"
                                                    class="w-full h-1.5 accent-white bg-white/30 rounded-full appearance-none cursor-pointer"
                                                    aria-label="{{ __('site.aria.volume') }}" />
                                          </div>
                                      </div>

                                  </div>
                              </div>
                              
                              <!-- CTA Button (Mobile) -->
                              <div class="order-3 lg:hidden flex justify-center mt-8 w-full">
                                    <a href="/noservices" class="group inline-flex items-center gap-3  bg-[#4f6ba3] text-white px-8 py-4 rounded-full font-bold shadow-xl hover:shadow-2xl  hover:bg-[#324d78] hover:scale-105  transition-all duration-300 transform hover:-translate-y-1">
                                      {{ __('site.services.cta_discover') }}
                                      <span class="w-8 h-8 rounded-full bg-white text-[#4f6ba3]  flex items-center justify-center group-hover:bg-[#283043] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                      </span>
                                    </a>
                              </div>
                    </div>
                  </div>

                  <!-- Script de contrôle vidéo -->
                  <script>
                    document.addEventListener('DOMContentLoaded', () => {
                      const video = document.getElementById('servicesVideo');
                      const playBtn = document.getElementById('videoPlayPause');
                      const muteBtn = document.getElementById('videoMute');
                      const vol = document.getElementById('videoVolume');
                      const iconPlay = document.getElementById('iconPlay');
                      const iconPause = document.getElementById('iconPause');
                      const iconVolume = document.getElementById('iconVolume');
                      const iconMuted = document.getElementById('iconMuted');

                      if (!video) return;

                      // --- 1. Définition des fonctions de mise à jour de l'UI ---
                      const updPlay = (playing) => {
                        if(iconPlay) iconPlay.classList.toggle('hidden', playing);
                        if(iconPause) iconPause.classList.toggle('hidden', !playing);
                      };

                      const updMute = (muted) => {
                        if(iconVolume) iconVolume.classList.toggle('hidden', muted);
                        if(iconMuted) iconMuted.classList.toggle('hidden', !muted);
                      };

                      // --- 2. Logique "Simple" d'Autoplay avec Son ---
                      // On tente d'abord de jouer avec le son (muted = false).
                      // Si le navigateur bloque (erreur), on passe en muet et on relance.
                      
                      const initialVol = vol ? parseInt(vol.value, 10) / 100 : 0.5;
                      video.volume = Number.isFinite(initialVol) ? initialVol : 0.5;
                      video.muted = false; // Intention : Son activé

                      var playPromise = video.play();

                      if (playPromise !== undefined) {
                          playPromise.then(() => {
                              // Succès : La vidéo joue AVEC le son
                              console.log('Autoplay started with sound.');
                              updPlay(true);
                              updMute(false);
                          }).catch(error => {
                              // Echec : Le navigateur a bloqué le son
                              console.warn('Autoplay with sound blocked. Fallback to muted.');
                              video.muted = true; // On coupe le son pour permettre la lecture
                              video.play().then(() => {
                                  updPlay(true);
                                  updMute(true);
                              });
                          });
                      }

                      // --- 3. Gestionnaires d'événements (Boutons) ---

                      playBtn?.addEventListener('click', () => {
                        if (video.paused) {
                          video.play().then(() => updPlay(true)).catch(() => updPlay(false));
                        } else {
                          video.pause();
                          updPlay(false);
                        }
                      });

                      muteBtn?.addEventListener('click', () => {
                        video.muted = !video.muted;
                        updMute(video.muted);
                        // Si on réactive le son alors que le volume est à 0, on le met à 50%
                        if (!video.muted && video.volume === 0) {
                          video.volume = 0.5;
                          if(vol) vol.value = 50;
                        }
                      });

                      vol?.addEventListener('input', (e) => {
                        const v = Math.max(0, Math.min(100, parseInt(e.target.value, 10) || 0)) / 100;
                        video.volume = v;
                        video.muted = (v === 0);
                        updMute(video.muted);
                      });
                    });
                  </script>
            </section>

        <!-- End Services Section -->




        <section id="pourquoi" class="relative py-12 sm:py-16 md:py-20 lg:py-24 bg-white dark:bg-gray-900 overflow-hidden isolate">
          <!-- Background decorative elements -->
          <div class="pointer-events-none absolute -top-40 -right-40 w-72 sm:w-80 md:w-96 h-72 sm:h-80 md:h-96 bg-blue-500/10 dark:bg-blue-400/5 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-10 animate-blob"></div>
          <div class="pointer-events-none absolute -bottom-40 -left-40 w-64 sm:w-72 md:w-80 h-64 sm:h-72 md:h-80 bg-purple-500/10 dark:bg-purple-400/5 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-10 animate-blob animation-delay-2000"></div>
          <div class="pointer-events-none absolute top-1/4 left-1/2 w-56 sm:w-64 md:w-72 h-56 sm:h-64 md:h-72 bg-emerald-500/10 dark:bg-emerald-400/5 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-10 animate-blob animation-delay-4000"></div>

          <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
            <!-- Title -->
            <div class="text-center mb-10 sm:mb-12 md:mb-16 lg:mb-20">
              <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight px-4">
                  {!! __('site.why.title') !!}
                 <span class="block  w-50 h-1.5 bg-gradient-to-r from-[#4f6ba3] to-transparent dark:bg-gradient-to-r dark:from-blue-400 dark:to-transparent mx-auto mt-3  rounded-full"></span>

                </h1>              
              <p class="mt-4 sm:mt-5 md:mt-6 text-base sm:text-lg md:text-xl text-gray-600 dark:text-gray-300 max-w-4xl mx-auto px-4">
                {{ __('site.why.subtitle') }}
              </p>
            </div>

            <!-- Cards -->
            <div class="grid gap-5 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mt-8 sm:mt-10 md:mt-12">
             
            <!-- Card 1 -->
              <div class="group flex flex-col items-center text-center p-6 sm:p-7 md:p-8 border border-gray-200 dark:border-gray-800 rounded-xl sm:rounded-2xl bg-white dark:bg-gray-800 shadow-lg hover:shadow-2xl dark:hover:shadow-blue-500/20 transition-all duration-300 ease-in-out transform hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#4f6ba3] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-[#4f6ba3] to-blue-400 flex items-center justify-center text-white shadow-xl mb-5 sm:mb-6 relative z-10">
                 <svg class="w-7 h-7 sm:w-8 sm:h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>

                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 relative z-10">{{ __('site.why.c1_title') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base relative z-10">{{ __('site.why.c1_desc') }}</p>
              </div>

              <!-- Card 2 -->
              <div class="group flex flex-col items-center text-center p-6 sm:p-7 md:p-8 border border-gray-200 dark:border-gray-800 rounded-xl sm:rounded-2xl bg-white dark:bg-gray-800 shadow-lg hover:shadow-2xl dark:hover:shadow-blue-500/20 transition-all duration-300 ease-in-out transform hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#4f6ba3] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-[#4f6ba3] to-blue-400 flex items-center justify-center text-white shadow-xl mb-5 sm:mb-6 relative z-10">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 relative z-10">{{ __('site.why.c2_title') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base relative z-10">{{ __('site.why.c2_desc') }}</p>
              </div>

              <!-- Card 3 -->
              <div class="group flex flex-col items-center text-center p-6 sm:p-7 md:p-8 border border-gray-200 dark:border-gray-800 rounded-xl sm:rounded-2xl bg-white dark:bg-gray-800 shadow-lg hover:shadow-2xl dark:hover:shadow-blue-500/20 transition-all duration-300 ease-in-out transform hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#4f6ba3] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-[#4f6ba3] to-blue-400 flex items-center justify-center text-white shadow-xl mb-5 sm:mb-6 relative z-10">
                    <svg  class="w-7 h-7 sm:w-8 sm:h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                    </svg>

                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 relative z-10">{{ __('site.why.c3_title') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base relative z-10">{{ __('site.why.c3_desc') }}</p>
              </div>

              <!-- Card 4 -->
              <div class="group flex flex-col items-center text-center p-6 sm:p-7 md:p-8 border border-gray-200 dark:border-gray-800 rounded-xl sm:rounded-2xl bg-white dark:bg-gray-800 shadow-lg hover:shadow-2xl dark:hover:shadow-blue-500/20 transition-all duration-300 ease-in-out transform hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#4f6ba3] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-[#4f6ba3] to-blue-400 flex items-center justify-center text-white shadow-xl mb-5 sm:mb-6 relative z-10">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                    </svg>

                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 relative z-10">{{ __('site.why.c4_title') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base relative z-10">{{ __('site.why.c4_desc') }}</p>
              </div>

            </div>
          </div>
        </section>


        <style>
          @keyframes blob {
            0% {
              transform: translate(0, 0) scale(1);
            }
            33% {
              transform: translate(30px, -50px) scale(1.1);
            }
            66% {
              transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
              transform: translate(0, 0) scale(1);
            }
          }

          .animate-blob {
            animation: blob 7s infinite cubic-bezier(0.6, 0.01, 0.3, 0.9);
          }

          .animation-delay-2000 {
            animation-delay: 2s;
          }

          .animation-delay-4000 {
            animation-delay: 4s;
          }
        </style>



            <!-- Team Section -->
            <!-- <section id="team" class=" py-20 ">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Notre Équipe d’Experts</h2>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Découvrez les professionnels passionnés qui font la force d’Offitrade. Notre équipe pluridisciplinaire met son expertise au service de votre réussite, avec un accompagnement personnalisé et une exigence d’excellence.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                        <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center group hover:shadow-2xl transition">
                            <img src="/images/img1.jpg" alt="Dr. Hamza" loading="lazy" decoding="async" class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 group-hover:scale-105 transition">
                            <h3 class="mt-5 text-xl font-bold text-gray-900">Dr. Hamza</h3>
                            <p class="text-blue-700 font-medium mb-2">Pharmacien Responsable</p>
                            <p class="text-gray-600 text-sm mb-4">Expert en pharmacologie clinique, garant de la qualité et de la sécurité des traitements délivrés.</p>
                        </div>
                        <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center group hover:shadow-2xl transition">
                            <img src="/images/img2.jpg" alt="Abir" loading="lazy" decoding="async" class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 group-hover:scale-105 transition">
                            <h3 class="mt-5 text-xl font-bold text-gray-900">Abir</h3>
                            <p class="text-blue-700 font-medium mb-2">Préparatrice en Pharmacie</p>
                            <p class="text-gray-600 text-sm mb-4">Assure la préparation, le contrôle et la délivrance des prescriptions avec précision.</p>
                        </div>
                        <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center group hover:shadow-2xl transition">
                            <img src="/images/img3.jpg" alt="Charlotte" loading="lazy" decoding="async" class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 group-hover:scale-105 transition">
                            <h3 class="mt-5 text-xl font-bold text-gray-900">Charlotte</h3>
                            <p class="text-blue-700 font-medium mb-2">Conseillère Santé</p>
                            <p class="text-gray-600 text-sm mb-4">Accompagne les patients dans le choix de produits de santé et bien-être adaptés.</p>
                        </div>
                        <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center group hover:shadow-2xl transition">
                            <img src="/images/img1.jpg" alt="Julia" loading="lazy" decoding="async" class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 group-hover:scale-105 transition">
                            <h3 class="mt-5 text-xl font-bold text-gray-900">Julia</h3>
                            <p class="text-blue-700 font-medium mb-2">Gestionnaire de Stock</p>
                            <p class="text-gray-600 text-sm mb-4">Supervise les approvisionnements et la traçabilité des produits pharmaceutiques.</p>
                        </div>
                    </div>
                </div>
            </section> -->





            <!-- Blog Section -->
            <section id="blog" class="py-12 sm:py-16 md:py-20 bg-gradient-to-br from-[#4f6ba3] to-[#283043] text-white overflow-hidden relative dark:from-gray-900 dark:to-gray-800">
                    <!-- Décoration de fond (formes subtiles) -->
                <div class="absolute bottom-1/4 right-1/4 w-36 sm:w-40 md:w-48 h-36 sm:h-40 md:h-48 bg-white/10 rounded-full mix-blend-overlay animate-blob-2 animation-delay-2000"></div>
                <div class="absolute top-1/2 left-1/2 w-56 sm:w-64 md:w-80 h-56 sm:h-64 md:h-80 bg-white/10 rounded-full mix-blend-overlay animate-blob-3 animation-delay-4000"></div>

              <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <!-- Titre de section amélioré -->
                <div class="text-center mb-12 sm:mb-14 md:mb-16">
                  <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl font-extrabold text-white relative inline-block pb-3 sm:pb-4">
                    {{ __('site.blog.title') }}
                    <span class="absolute left-1/2 bottom-0 transform -translate-x-1/2 w-20 sm:w-24 md:w-28 h-1 sm:h-1.5 w-60 h-1.5 bg-gradient-to-r from-white to-transparent dark:bg-gradient-to-r dark:from-blue-400 dark:to-transparent  rounded-full"></span>
                  </h1>
                  <p class="mt-3 sm:mt-4 text-base sm:text-lg md:text-xl text-white max-w-2xl mx-auto px-4">
                    {{ __('site.blog.subtitle') }}
                  </p>
                </div>

                <div class="grid gap-6 sm:gap-8 md:gap-10 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                  @forelse($posts as $post)
                    @php
                      $img = $post->cover_image ? Storage::url($post->cover_image) : asset('images/img1.jpg');
                    @endphp

                    <article class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:scale-[1.03] hover:shadow-2xl group relative border-l-4 border-[#4f6ba3]">                      
                                <a href="{{ route('pages.blog.show', $post) }}" class="block focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4f6ba3]">
                        <div class="relative overflow-hidden">
                          <img src="{{ $img }}" alt="{{ $post->title }}" loading="lazy" decoding="async" class="w-full h-48 sm:h-52 md:h-56 object-cover transform group-hover:scale-110 transition-transform duration-500 ease-in-out">
                          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                          <span class="absolute top-3 sm:top-4 left-3 sm:left-4 bg-[#4f6ba3] text-white text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full shadow-md">
                            {{ $post->category->name ?? __('site.blog.category_fallback') }}
                          </span>
                        </div>
                        <div class="p-4 sm:p-5 md:p-6">
                          <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-[#5a7bbf] transition-colors duration-300 line-clamp-2">
                            {{ $post->title }}
                          </h3>
                          <p class="text-gray-600 text-sm sm:text-base mb-3 sm:mb-4 line-clamp-3">
                            {{ $post->content ? \Illuminate\Support\Str::limit(strip_tags($post->content), 150) : __('site.blog.no_content') }}
                          </p>
                          <span class="inline-flex items-center text-[#4f6ba3] text-sm sm:text-base font-semibold hover:underline group-hover:translate-x-1 transition-transform duration-300">
                            {{ __('site.blog.read_more') }}
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                          </span>
                        </div>
                      </a>
                    </article>
                  @empty
                    <div class="col-span-full text-center text-white/90 py-8">
                      {{ __('site.blog.empty') }}
                    </div>
                  @endforelse
                </div>

                <!-- Bouton "Voir tous les articles" -->
                <div class="text-center mt-12 sm:mt-14 md:mt-16">
                  <a href="/blog" 
                    class="inline-flex items-center bg-[#4f6ba3] text-white font-bold py-2.5 sm:py-3 px-6 sm:px-8 rounded-full shadow-lg text-sm sm:text-base
                            hover:bg-[#324d78] hover:scale-105 active:scale-95 transition-all duration-300 transform">
                    {{ __('site.blog.see_all') }}
                    <svg class="ml-2 w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                  </a>
                </div>

              </div>
            </section>



            <!-- FAQ Section -->
            <!-- FAQ Section -->
          <section id="faq" class="bg-white dark:bg-gray-900 py-12 sm:py-16 md:py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
              <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl font-bold text-[#4f6ba3] dark:text-blue-400 text-center mb-4 sm:mb-5 md:mb-6">{{ __('site.faq.title') }}
              <span class="block w-40 h-1.5 bg-gradient-to-r from-[#4f6ba3] to-transparent dark:bg-gradient-to-r dark:from-blue-400 dark:to-transparent mx-auto mt-3 rounded-full"></span>
              </h1>

              @php
              if (! function_exists('sanitize_faq')) {
                function sanitize_faq($s) {
                  $s = (string) $s;
                  // Collapse line breaks and multiple spaces
                  $s = preg_replace("/[\r\n]+/u", ' ', $s);
                  $s = preg_replace('/\s+/u', ' ', $s);
                  // Protect French guillemets with non-breaking spaces
                  $s = preg_replace('/«\s*/u', '«&nbsp;', $s);
                  $s = preg_replace('/\s*»/u', '&nbsp;»', $s);
                  // Ensure non-breaking space before French punctuation ? ! : ; to avoid lonely punctuation on a new line
                  $s = preg_replace('/\s+([?!:;])/u', '&nbsp;$1', $s);
                  return trim($s);
                }
              }
              @endphp

              <p class="text-center text-base sm:text-lg md:text-lg text-gray-600 dark:text-gray-300 mb-8 sm:mb-10 md:mb-12 mx-auto max-w-3xl px-4">{!! sanitize_faq(__('site.faq.subtitle')) !!}</p>

              <div class="space-y-4 sm:space-y-5 md:space-y-6">

              <details class="group border-b-2 border-[#4f6ba3]/20 dark:border-[#5a7bbf]/30 pb-3 sm:pb-4 hover:border-[#4f6ba3]/40 dark:hover:border-[#5a7bbf]/50 transition-colors">
                <summary class="flex justify-between items-center cursor-pointer text-gray-900 dark:text-white font-medium text-base sm:text-lg md:text-lg">
                        <span class="max-w-prose">{!! sanitize_faq(__('site.faq.q1')) !!}</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </summary>
                <p class="mt-2 text-gray-600 dark:text-gray-300 text-base sm:text-lg max-w-prose">{!! sanitize_faq(__('site.faq.a1')) !!}</p>
              </details>

              <details class="group border-b-2 border-[#4f6ba3]/20 dark:border-[#5a7bbf]/30 pb-3 sm:pb-4 hover:border-[#4f6ba3]/40 dark:hover:border-[#5a7bbf]/50 transition-colors">
                <summary class="flex justify-between items-center cursor-pointer text-gray-900 dark:text-white font-medium text-base sm:text-lg md:text-lg">
                        <span class="max-w-prose">{!! sanitize_faq(__('site.faq.q2')) !!}</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </summary>
                <p class="mt-2 text-gray-600 dark:text-gray-300 text-base sm:text-lg max-w-prose">{!! sanitize_faq(__('site.faq.a2')) !!}</p>
                    </details>

              <details class="group border-b-2 border-[#4f6ba3]/20 dark:border-[#5a7bbf]/30 pb-3 sm:pb-4 hover:border-[#4f6ba3]/40 dark:hover:border-[#5a7bbf]/50 transition-colors">
                <summary class="flex justify-between items-center cursor-pointer text-gray-900 dark:text-white font-medium text-base sm:text-lg md:text-lg">
                        <span class="max-w-prose">{!! sanitize_faq(__('site.faq.q3')) !!}</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </summary>
                <p class="mt-2 text-gray-600 dark:text-gray-300 text-base sm:text-lg max-w-prose">{!! sanitize_faq(__('site.faq.a3')) !!}</p>
                    </details>

              <details class="group border-b-2 border-[#4f6ba3]/20 dark:border-[#5a7bbf]/30 pb-3 sm:pb-4 hover:border-[#4f6ba3]/40 dark:hover:border-[#5a7bbf]/50 transition-colors">
                <summary class="flex justify-between items-center cursor-pointer text-gray-900 dark:text-white font-medium text-base sm:text-lg md:text-lg">
                        <span class="max-w-prose">{!! sanitize_faq(__('site.faq.q4')) !!}</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </summary>
                <p class="mt-2 text-gray-600 dark:text-gray-300 text-base sm:text-lg max-w-prose">{!! sanitize_faq(__('site.faq.a4')) !!}</p>
                    </details>

              <details class="group border-b-2 border-[#4f6ba3]/20 dark:border-[#5a7bbf]/30 pb-3 sm:pb-4 hover:border-[#4f6ba3]/40 dark:hover:border-[#5a7bbf]/50 transition-colors">
                <summary class="flex justify-between items-center cursor-pointer text-gray-900 dark:text-white font-medium text-base sm:text-lg md:text-lg">
                        <span class="max-w-prose">{!! sanitize_faq(__('site.faq.q5')) !!}</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </summary>
                <p class="mt-2 text-gray-600 dark:text-gray-300 text-base sm:text-lg max-w-prose">{!! sanitize_faq(__('site.faq.a5')) !!}</p>
                    </details>

                  </div>
                </div>
          </section>

            <!-- Contact Section -->
          <section id="contact" class="bg-white text-gray-800 py-12 sm:py-14 md:py-16 dark:bg-gray-900 dark:text-gray-100">
              <div class="max-w-5xl mx-auto px-4 sm:px-6">
                  <div class="text-center mb-10 sm:mb-12">
                      <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl font-bold mb-3 sm:mb-4 text-[#4f6ba3] dark:text-blue-400">{{ __('site.contact.title') }}
                        <span class="block w-40 h-1.5 bg-gradient-to-r from-[#4f6ba3] to-transparent dark:bg-gradient-to-r dark:from-blue-400 dark:to-transparent mx-auto mt-3 rounded-full"></span>

                      </h1>
                      <p class="text-base sm:text-lg dark:text-gray-300 px-4">
                          {{ __('site.contact.subtitle') }}
                      </p>
                  </div>

                  <div class="flex flex-col lg:flex-row gap-6 sm:gap-8">
                      <div class="w-full lg:w-1/2 flex flex-col">
                          <p class="text-sm sm:text-base text-gray-600 mb-5 sm:mb-6 dark:text-gray-300">{!! __('site.contact.desc') !!}</p>
                          
                          <div class="mb-5 sm:mb-6 px-2 sm:px-4 py-2">
                              <div class="flex items-start mb-5 sm:mb-6 space-x-3 sm:space-x-4">
                                  <span class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-[#4f6ba3]/10 text-[#4f6ba3]">
                                      <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-6.5-5.086-6.5-10.5A6.5 6.5 0 0 1 18.5 10.5C18.5 15.914 12 21 12 21Z"/><circle cx="12" cy="10.5" r="2.5"/></svg>
                                  </span>
                                  <div>
                                      <h4 class="text-base sm:text-lg font-semibold dark:text-white">{{ __('site.contact.address') }}</h4>
                                      <p class="text-sm sm:text-base dark:text-gray-300">{{ $siteSettings?->address ?? '14 rue Beffory, 92200 Neuilly-sur-Seine, France' }}</p>
                                  </div>
                              </div>
                              <div class="flex items-start mb-5 sm:mb-6 space-x-3 sm:space-x-4">
                                  <span class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-[#4f6ba3]/10 text-[#4f6ba3]">
                                      <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92V21a1 1 0 0 1-1.09 1A19.86 19.86 0 0 1 3 5.09 1 1 0 0 1 4 4h4.09a1 1 0 0 1 1 .75 12.44 12.44 0 0 0 .7 2.22 1 1 0 0 1-.23 1L8.09 9.91a16 16 0 0 0 6 6l1.94-1.49a1 1 0 0 1 1-.12 12.44 12.44 0 0 0 2.22.7 1 1 0 0 1 .75 1z"/></svg>
                                  </span>
                                  <div>
                                      <h4 class="text-base sm:text-lg font-semibold dark:text-white">{{ __('site.contact.phone') }}</h4>
                                      <p class="text-sm sm:text-base dark:text-gray-300">{{ $siteSettings?->phone ?? '+33 07 67 70 67 26 ' }}</p>
                                  </div>
                              </div>
                              <div class="flex items-start mb-5 sm:mb-6 space-x-3 sm:space-x-4">
                                  <span class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-[#4f6ba3]/10 text-[#4f6ba3]">
                                      <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M22 6.92V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6.92a2 2 0 0 1 .76-1.56L12 12l9.24-6.64A2 2 0 0 1 22 6.92Z"/></svg>
                                  </span>
                                  <div>
                                      <h4 class="text-base sm:text-lg font-semibold dark:text-white">{{ __('site.contact.email') }}</h4>
                                      <p class="text-sm sm:text-base dark:text-gray-300">{{ $siteSettings?->email ?? 'contact@offitrade.fr' }}</p>
                                  </div>
                              </div>
                          </div>

                          <div class="mt-auto w-full h-54 rounded-lg shadow-lg overflow-hidden flex-grow">
                              @php
                                  // Get the address, with a fallback for safety
                                  $address = $siteSettings?->address ?? '14 rue Beffory, 92200 Neuilly-sur-Seine, France';
                              @endphp
                              <iframe
                                  width="100%"
                                  height="100%"
                                  style="border:0;"
                                  loading="lazy"
                                  allowfullscreen
                                  src="https://maps.google.com/maps?q={{ urlencode($address) }}&t=&z=15&ie=UTF8&iwloc=&output=embed">
                              </iframe>
                          </div>
                      </div>
                      
                      <div class="w-full lg:w-1/2 bg-gray-50 p-5 sm:p-6 rounded-lg shadow-lg dark:bg-gray-800">
                          <form method="POST" action="{{ route('contact.submit') }}" class="space-y-3 sm:space-y-4" id="contact-form-home">
                              @csrf
                              <div>
                                  <label class="block text-gray-700 font-medium text-sm sm:text-base dark:text-gray-200">{{ __('site.contact.form.name') }}</label>
                                  <input name="name" type="text" value="{{ old('name') }}" required placeholder="{{ __('site.contact.form.name') }}"
                                      pattern="[\p{L}\s'\-]{1,100}"
                                      class="w-full mt-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                                  @error('name')<p class="text-red-500 text-xs sm:text-sm">{{ $message }}</p>@enderror
                              </div>
                              <div>
                                  <label class="block text-gray-700 font-medium text-sm sm:text-base dark:text-gray-200">{{ __('site.contact.form.email') }}</label>
                                  <input name="email" type="email" value="{{ old('email') }}" required placeholder="{{ __('site.contact.form.email') }}"
                                      class="w-full mt-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                                  @error('email')<p class="text-red-500 text-xs sm:text-sm">{{ $message }}</p>@enderror
                              </div>
                              <div>
                                  <label class="block text-gray-700 font-medium text-sm sm:text-base dark:text-gray-200">{{ __('site.contact.form.phone') }}</label>
                                  <input name="phone" type="tel" value="{{ old('phone') }}" required placeholder="{{ __('site.contact.form.phone') }}"
                                      pattern="[0-9+\-().\s]{6,20}"
                                      class="w-full mt-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                                  @error('phone')<p class="text-red-500 text-xs sm:text-sm">{{ $message }}</p>@enderror
                              </div>
                              <div>
                                  <label class="block text-gray-700 font-medium text-sm sm:text-base dark:text-gray-200">{{ __('site.contact.form.you_are') }}</label>
                                  <select id="user_type" name="user_type" required
                                          class="w-full mt-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                                      <option value="" disabled {{ old('user_type') ? '' : 'selected' }}>{{ __('site.contact.form.choose_option') }}</option>
                                      <option value="Acheteur" {{ old('user_type')=='Acheteur' ? 'selected' : '' }}>{{ __('site.contact.form.buyer') }}</option>
                                      <option value="Futur pharmacien" {{ old('user_type')=='Futur pharmacien' ? 'selected' : '' }}>{{ __('site.contact.form.future_pharmacist') }}</option>
                                      <option value="Pharmacien titulaire" {{ old('user_type')=='Pharmacien titulaire' ? 'selected' : '' }}>{{ __('site.contact.form.owner_pharmacist') }}</option>
                                      <option value="Autres" {{ old('user_type')=='Autres' ? 'selected' : '' }}>{{ __('site.contact.form.others') }}</option>
                                  </select>
                                  @error('user_type')<p class="text-red-500 text-xs sm:text-sm">{{ $message }}</p>@enderror
                              </div>
                              <div id="other-field-container" class="{{ old('user_type') == 'Autres' ? '' : 'hidden' }}">
                                  <label class="block text-gray-700 font-medium text-sm sm:text-base dark:text-gray-200">{{ __('site.contact.form.specify') }}</label>
                                  <input name="user_other" type="text" value="{{ old('user_other') }}" placeholder="{{ __('site.contact.form.specify_placeholder') }}"
                                      pattern="[\p{L}\s'\-]{0,100}"
                                      class="w-full mt-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                              </div>
                              <div>
                                  <label class="block text-gray-700 font-medium text-sm sm:text-base dark:text-gray-200">{{ __('site.contact.form.message') }}</label>
                                  <textarea name="message" required rows="4" placeholder="{{ __('site.contact.form.ph_message') }}" maxlength="1500"
                                      class="w-full mt-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100" id="message-input-home">{{ old('message') }}</textarea>
                                  <div class="text-right text-xs text-gray-500"><span id="message-count-home">0</span>/1500</div>
                                  @error('message')<p class="text-red-500 text-xs sm:text-sm">{{ $message }}</p>@enderror
                              </div>
                              <button type="submit"
                                      class="w-full bg-[#4f6ba3] text-white py-2 text-sm sm:text-base rounded-md hover:bg-[#a1b6d8] transition flex items-center justify-center gap-2" id="contact-submit-btn">
                                  <svg data-button-spinner class="hidden h-4 w-4 sm:h-5 sm:w-5 animate-spin text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
                                  </svg>
                                  <span data-button-label>{{ __('site.contact.form.submit') }}</span>
                              </button>
                              
                              {{-- Success message shown at bottom of form --}}
                              @if(session('success'))
                                  <div id="contact-success-home" class="mt-3 sm:mt-4 p-3 sm:p-4 bg-green-200 text-green-800 text-sm sm:text-base">{{ session('success') }}</div>
                              @endif
                          </form>

                          <script>
                              // ... votre JavaScript reste inchangé ...
                              document.getElementById('user_type').addEventListener('change', function () {
                                  var otherField = document.getElementById('other-field-container');
                                  if (this.value === 'Autres') {
                                      otherField.classList.remove('hidden');
                                  } else {
                                      otherField.classList.add('hidden');
                                  }
                              });
                              (function(){
                                  var ta = document.getElementById('message-input-home');
                                  var counter = document.getElementById('message-count-home');
                                  if (ta && counter) {
                                      var update = function(){ counter.textContent = ta.value.length; };
                                      ta.addEventListener('input', update);
                                      update();
                                  }
                              })();
                              (function(){
                                  var form = document.getElementById('contact-form-home');
                                  if (!form) return;
                                  var submitBtn = document.getElementById('contact-submit-btn');
                                  if (!submitBtn) return;
                                  var spinner = submitBtn.querySelector('[data-button-spinner]');
                                  var label = submitBtn.querySelector('[data-button-label]');
                                  var originalText = label ? label.textContent : submitBtn.textContent;
                                  form.addEventListener('submit', function(){
                                      try {
                                          submitBtn.disabled = true;
                                          submitBtn.classList.add('opacity-80','cursor-not-allowed');
                                          if (spinner) spinner.classList.remove('hidden');
                                          if (label) label.textContent = 'Envoi...';
                                      } catch (e) { console && console.warn && console.warn(e); }
                                  });
                                  form.addEventListener('turbo:submit-end', function(){
                                      try {
                                          submitBtn.disabled = false;
                                          submitBtn.classList.remove('opacity-80','cursor-not-allowed');
                                          if (spinner) spinner.classList.add('hidden');
                                          if (label) label.textContent = originalText;
                                      } catch (e) { console && console.warn && console.warn(e); }
                                  });
                                  var elements = form.querySelectorAll('input,textarea,select');
                                  elements.forEach(function(el){
                                      el.addEventListener('invalid', function(){
                                          try {
                                              submitBtn.disabled = false;
                                              submitBtn.classList.remove('opacity-80','cursor-not-allowed');
                                              if (spinner) spinner.classList.add('hidden');
                                              if (label) label.textContent = originalText;
                                          } catch(e) { /* ignore */ }
                                      }, {once: true});
                                  });
                              })();
                          </script>
                      </div>
                  </div>
              </div>
          </section>

    <main>
    <!-- Fin Main Content -->



        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
  </body>
      <!-- Footer -->
    @include('layouts.footer') 
    <!-- Fin Footer -->
  @if(session('success'))
  <script>
    // If the server-side redirect with #contact didn't work (some browsers strip fragments),
    // ensure we scroll to the contact section when a success flash exists.
    (function(){
      try {
        var el = document.getElementById('contact');
        if (el) {
          // small delay to allow browser to render and to ensure the element is in DOM
          setTimeout(function(){ el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 200);
        }
      } catch (e) {
        console && console.warn && console.warn('Scroll to contact failed', e);
      }
    })();
  </script>
  @endif
</html>

