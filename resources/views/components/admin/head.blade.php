<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>@yield('title', 'Admin Dashboard') - Laptop Ecommerce</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    body {
        font-family: 'Manrope', sans-serif;
        background-color: #0e0e0e;
    }
    h1, h2, h3, .font-headline {
        font-family: 'Space Grotesk', sans-serif;
    }
    ::-webkit-scrollbar {
        width: 4px;
    }
    ::-webkit-scrollbar-track {
        background: #0e0e0e;
    }
    ::-webkit-scrollbar-thumb {
        background: #262626;
        border-radius: 10px;
    }
    .glass-panel {
        background: rgba(38, 38, 38, 0.4);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(72, 72, 71, 0.15);
    }
</style>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#a1faff",
                    "secondary": "#ff7524",
                    "tertiary": "#64b3ff",
                    "surface": "#0e0e0e",
                    "on-surface": "#ffffff",
                    "error": "#ff716c"
                },
                borderRadius: {
                    "DEFAULT": "1rem",
                    "lg": "2rem",
                    "xl": "3rem"
                }
            }
        }
    }
</script>
@stack('styles')
