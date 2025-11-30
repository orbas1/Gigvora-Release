import 'package:flutter/material.dart';

/// Central Gigvora theme that mirrors the web design tokens in
/// `resources/css/gigvora/tokens.css`.
///
/// This should be used as the host `ThemeData` so that all addon
/// packages inherit consistent colours, typography, and component
/// styles.
class GigvoraTheme {
  static const _primary = Color(0xFF2563EB); // --gv-color-primary-600
  static const _primaryVariant = Color(0xFF1D4ED8); // --gv-color-primary-700
  static const _neutral50 = Color(0xFFF8FAFC); // --gv-color-neutral-50
  static const _neutral900 = Color(0xFF0F172A); // --gv-color-neutral-900

  static ThemeData light() {
    final colorScheme = ColorScheme.fromSeed(
      seedColor: _primary,
      primary: _primary,
      primaryContainer: _primaryVariant,
      secondary: const Color(0xFF0EA5E9), // info
      surface: Colors.white,
      onPrimary: Colors.white,
      onSecondary: _neutral900,
      onSurface: _neutral900,
      error: const Color(0xFFDC2626),
    );

    const baseFontFamily = 'Inter';

    final textTheme = Typography.blackMountainView.copyWith(
      displayLarge: const TextStyle(
        fontFamily: baseFontFamily,
        fontWeight: FontWeight.w600,
        fontSize: 32,
        height: 1.25,
      ),
      headlineMedium: const TextStyle(
        fontFamily: baseFontFamily,
        fontWeight: FontWeight.w600,
        fontSize: 24,
        height: 1.3,
      ),
      titleMedium: const TextStyle(
        fontFamily: baseFontFamily,
        fontWeight: FontWeight.w600,
        fontSize: 16,
      ),
      bodyMedium: const TextStyle(
        fontFamily: baseFontFamily,
        fontWeight: FontWeight.w400,
        fontSize: 14,
        height: 1.5,
      ),
      bodySmall: const TextStyle(
        fontFamily: baseFontFamily,
        fontWeight: FontWeight.w400,
        fontSize: 12,
        height: 1.5,
      ),
      labelLarge: const TextStyle(
        fontFamily: baseFontFamily,
        fontWeight: FontWeight.w600,
        fontSize: 14,
        letterSpacing: 0.08,
      ),
    );

    return ThemeData(
      colorScheme: colorScheme,
      scaffoldBackgroundColor: _neutral50,
      useMaterial3: true,
      textTheme: textTheme,
      fontFamily: baseFontFamily,
      appBarTheme: AppBarTheme(
        elevation: 0,
        backgroundColor: Colors.white.withValues(alpha: 0.9),
        foregroundColor: _neutral900,
        centerTitle: false,
        titleTextStyle: textTheme.titleMedium,
      ),
      cardTheme: CardThemeData(
        color: Colors.white,
        elevation: 2,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: BorderSide(
            color: const Color(0xFF94A3B8).withValues(alpha: 0.35),
          ),
        ),
        margin: const EdgeInsets.all(12),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          elevation: 1,
          backgroundColor: _primary,
          foregroundColor: Colors.white,
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          textStyle: textTheme.labelLarge,
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: _primary,
          textStyle: textTheme.labelLarge,
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: Colors.white,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(
            color: Color(0xFFCBD5F5),
          ),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(
            color: _primary,
            width: 1.6,
          ),
        ),
      ),
      chipTheme: ChipThemeData(
        backgroundColor: const Color(0xFFE2E8F0),
        selectedColor: _primary.withValues(alpha: 0.08),
        disabledColor: const Color(0xFFE2E8F0),
        labelStyle: textTheme.bodySmall!.copyWith(
          fontWeight: FontWeight.w600,
        ),
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(999),
          side: BorderSide(
            color: const Color(0xFF94A3B8).withValues(alpha: 0.35),
          ),
        ),
      ),
      iconTheme: const IconThemeData(color: _neutral900),
      dividerTheme: const DividerThemeData(
        color: Color(0xFFCBD5F5),
        thickness: 1,
      ),
    );
  }
}


