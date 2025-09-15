import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/livewire/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './filament/**/*.php',
        './vendor/koalafacade/filament-alertbox/**/*.blade.php',
        "./resources/**/*.blade.php",
        './vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
    ],
}
