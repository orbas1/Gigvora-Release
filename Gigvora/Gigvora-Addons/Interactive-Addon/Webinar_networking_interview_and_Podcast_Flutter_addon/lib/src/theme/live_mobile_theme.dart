import 'package:flutter/material.dart';

/// Shared styling helpers to align the Live & Events addon with Gigvora's
/// mobile design system.
class LiveMobileTheme {
  static const double cardRadius = 12;

  static RoundedRectangleBorder cardShape([double radius = cardRadius]) =>
      RoundedRectangleBorder(borderRadius: BorderRadius.circular(radius));

  static EdgeInsets get screenPadding => const EdgeInsets.symmetric(horizontal: 16, vertical: 12);

  static Color surfaceVariant(BuildContext context) => Theme.of(context).colorScheme.surfaceVariant;

  static Color mutedText(BuildContext context) => Theme.of(context).colorScheme.onSurface.withOpacity(0.64);

  static Color metaText(BuildContext context) => Theme.of(context).colorScheme.onSurface.withOpacity(0.54);

  static Color accent(BuildContext context) => Theme.of(context).colorScheme.primary;

  static Color emphasisIcon(BuildContext context) => Theme.of(context).colorScheme.primaryContainer;

  static Color danger(BuildContext context) => Theme.of(context).colorScheme.error;
}
