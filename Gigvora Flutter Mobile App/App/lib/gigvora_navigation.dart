import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

/// Data models for the `/api/navigation` payload so Flutter can mirror the web IA.
class GigvoraNavItem {
  final String key;
  final String label;
  final String route;
  final String? match;
  final String? icon;

  const GigvoraNavItem({
    required this.key,
    required this.label,
    required this.route,
    this.match,
    this.icon,
  });

  factory GigvoraNavItem.fromJson(Map<String, dynamic> json) => GigvoraNavItem(
        key: json['key'] as String? ?? json['route'] as String? ?? '',
        label: json['label'] as String? ?? '',
        route: json['route'] as String? ?? '',
        match: json['match'] as String?,
        icon: json['icon'] as String?,
      );
}

class GigvoraNavGroup {
  final String key;
  final String label;
  final String? icon;
  final List<GigvoraNavItem> children;

  const GigvoraNavGroup({
    required this.key,
    required this.label,
    required this.children,
    this.icon,
  });

  factory GigvoraNavGroup.fromJson(Map<String, dynamic> json) => GigvoraNavGroup(
        key: json['key'] as String? ?? '',
        label: json['label'] as String? ?? '',
        icon: json['icon'] as String?,
        children: (json['children'] as List<dynamic>? ?? const [])
            .map((item) => GigvoraNavItem.fromJson(item as Map<String, dynamic>))
            .toList(),
      );
}

class GigvoraDrawerSection {
  final String key;
  final String label;
  final List<GigvoraNavItem> children;

  const GigvoraDrawerSection({
    required this.key,
    required this.label,
    required this.children,
  });

  factory GigvoraDrawerSection.fromJson(Map<String, dynamic> json) => GigvoraDrawerSection(
        key: json['key'] as String? ?? '',
        label: json['label'] as String? ?? '',
        children: (json['children'] as List<dynamic>? ?? const [])
            .map((item) => GigvoraNavItem.fromJson(item as Map<String, dynamic>))
            .toList(),
      );
}

class GigvoraMobileNav {
  final List<GigvoraNavItem> tabs;
  final List<GigvoraDrawerSection> drawer;

  const GigvoraMobileNav({
    required this.tabs,
    required this.drawer,
  });

  factory GigvoraMobileNav.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const GigvoraMobileNav(tabs: [], drawer: []);
    }

    return GigvoraMobileNav(
      tabs: (json['tabs'] as List<dynamic>? ?? const [])
          .map((item) => GigvoraNavItem.fromJson(item as Map<String, dynamic>))
          .toList(),
      drawer: (json['drawer'] as List<dynamic>? ?? const [])
          .map((item) => GigvoraDrawerSection.fromJson(item as Map<String, dynamic>))
          .toList(),
    );
  }
}

class GigvoraNavigationConfig {
  final List<GigvoraNavItem> primary;
  final List<GigvoraNavGroup> groups;
  final List<GigvoraNavItem> secondary;
  final List<GigvoraNavItem> admin;
  final List<GigvoraNavItem> profileTabs;
  final List<GigvoraNavItem> settings;
  final GigvoraMobileNav mobile;

  const GigvoraNavigationConfig({
    required this.primary,
    required this.groups,
    required this.secondary,
    required this.admin,
    required this.profileTabs,
    required this.settings,
    required this.mobile,
  });

  factory GigvoraNavigationConfig.fromJson(Map<String, dynamic> json) {
    List<GigvoraNavItem> _parseItems(String key) {
      return (json[key] as List<dynamic>? ?? const [])
          .map((item) => GigvoraNavItem.fromJson(item as Map<String, dynamic>))
          .toList();
    }

    return GigvoraNavigationConfig(
      primary: _parseItems('primary'),
      groups: (json['groups'] as List<dynamic>? ?? const [])
          .map((item) => GigvoraNavGroup.fromJson(item as Map<String, dynamic>))
          .toList(),
      secondary: _parseItems('secondary'),
      admin: _parseItems('admin'),
      profileTabs: _parseItems('profile_tabs'),
      settings: _parseItems('settings'),
      mobile: GigvoraMobileNav.fromJson(json['mobile'] as Map<String, dynamic>?),
    );
  }

  bool get hasMobileTabs => mobile.tabs.isNotEmpty;
}

class GigvoraNavigationException implements Exception {
  final String message;
  final Object? cause;

  GigvoraNavigationException(this.message, [this.cause]);

  @override
  String toString() => 'GigvoraNavigationException($message)';
}

class GigvoraNavigationClient {
  GigvoraNavigationClient({
    required this.baseUrl,
    required this.tokenProvider,
    http.Client? httpClient,
  }) : _httpClient = httpClient ?? http.Client();

  final String baseUrl;
  final Future<String?> Function() tokenProvider;
  final http.Client _httpClient;

  Uri _navigationUri() {
    final normalized = baseUrl.endsWith('/') ? baseUrl.substring(0, baseUrl.length - 1) : baseUrl;
    return Uri.parse('$normalized/api/navigation');
  }

  Future<GigvoraNavigationConfig> fetch({Duration timeout = const Duration(seconds: 15)}) async {
    final token = await tokenProvider();
    final response = await _httpClient
        .get(
          _navigationUri(),
          headers: {
            'Accept': 'application/json',
            if (token != null && token.isNotEmpty) 'Authorization': 'Bearer $token',
          },
        )
        .timeout(timeout);

    if (response.statusCode >= 400) {
      throw GigvoraNavigationException(
        'Failed to load navigation (status ${response.statusCode})',
      );
    }

    final decoded = jsonDecode(response.body) as Map<String, dynamic>;
    final data = decoded['data'] as Map<String, dynamic>? ?? decoded;
    return GigvoraNavigationConfig.fromJson(data);
  }
}

IconData gigvoraNavIcon(String? key) {
  switch (key) {
    case 'home':
      return Icons.home_outlined;
    case 'briefcase':
      return Icons.work_outline;
    case 'handshake':
      return Icons.handshake_outlined;
    case 'broadcast':
      return Icons.podcasts_outlined;
    case 'users':
      return Icons.groups_outlined;
    case 'layers':
      return Icons.layers_outlined;
    case 'chat':
      return Icons.chat_bubble_outline;
    case 'megaphone':
      return Icons.campaign_outlined;
    case 'sparkles':
      return Icons.auto_awesome_outlined;
    case 'bell':
      return Icons.notifications_outlined;
    case 'bookmark':
      return Icons.bookmark_outline;
    case 'calendar':
      return Icons.calendar_month_outlined;
    case 'search':
      return Icons.search;
    case 'user-circle':
      return Icons.account_circle_outlined;
    case 'settings':
      return Icons.settings_outlined;
    case 'shield':
      return Icons.shield_outlined;
    default:
      return Icons.apps_outlined;
  }
}

extension GigvoraNavigationUi on GigvoraNavigationConfig {
  List<BottomNavigationBarItem> toBottomNavItems({bool showLabels = true}) {
    return mobile.tabs
        .map(
          (item) => BottomNavigationBarItem(
            icon: Icon(gigvoraNavIcon(item.icon)),
            label: showLabels ? item.label : '',
          ),
        )
        .toList();
  }

  /// Convenience helper to expose drawer sections in UI code.
  List<GigvoraDrawerSection> drawerSections() => mobile.drawer;
}


