<?php

namespace SuperAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use SuperAuth\Core\ThemeManager;

class ThemeController extends Controller
{
    protected ThemeManager $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * Toggle theme between light and dark
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'theme' => 'required|in:light,dark,auto'
        ]);

        $theme = $request->input('theme');
        
        if ($theme === 'auto') {
            // Remove theme preference to use system preference
            session()->forget('superauth_theme');
        } else {
            $this->themeManager->setCurrentTheme($theme);
        }

        return response()->json([
            'success' => true,
            'theme' => $this->themeManager->getCurrentTheme(),
            'message' => "Theme switched to {$theme}"
        ]);
    }

    /**
     * Get current theme
     */
    public function getCurrent(): JsonResponse
    {
        return response()->json([
            'theme' => $this->themeManager->getCurrentTheme(),
            'available_themes' => $this->themeManager->getAvailableThemes()
        ]);
    }

    /**
     * Set specific theme
     */
    public function set(Request $request): JsonResponse
    {
        $request->validate([
            'theme' => 'required|string|in:' . implode(',', $this->themeManager->getAvailableThemes())
        ]);

        $theme = $request->input('theme');
        
        if ($this->themeManager->setCurrentTheme($theme)) {
            return response()->json([
                'success' => true,
                'theme' => $theme,
                'message' => "Theme set to {$theme}"
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid theme'
        ], 400);
    }
}
