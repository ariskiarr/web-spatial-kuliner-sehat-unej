<svg {{ $attributes }} viewBox="0 0 200 50" xmlns="http://www.w3.org/2000/svg">
    <!-- Background -->
    <rect width="200" height="50" rx="8" fill="url(#gradient)" />

    <!-- Gradient Definition -->
    <defs>
        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#0891b2;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#0e7490;stop-opacity:1" />
        </linearGradient>
    </defs>

    <!-- Map Pin Icon -->
    <path d="M20 15 C20 11 23 8 27 8 C31 8 34 11 34 15 C34 20 27 28 27 28 C27 28 20 20 20 15 Z"
          fill="white" opacity="0.9"/>
    <circle cx="27" cy="15" r="3" fill="#0891b2"/>

    <!-- Fork and Knife Icon -->
    <g transform="translate(38, 12)">
        <!-- Fork -->
        <path d="M2 2 L2 12 M0 2 L0 8 C0 9 1 10 2 10 M4 2 L4 8 C4 9 3 10 2 10"
              stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/>
        <!-- Knife -->
        <path d="M8 2 L8 12 M8 2 C8 2 10 4 10 6 C10 8 8 8 8 8"
              stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/>
    </g>

    <!-- Text: CalorieMaps -->
    <text x="58" y="32" font-family="Arial, sans-serif" font-size="18" font-weight="bold" fill="white">
        CalorieMaps
    </text>
</svg>
