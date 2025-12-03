import 'package:flutter/material.dart';

import 'gigvora_navigation.dart';

@immutable
class DeepLinkResolution {
  final String routeName;
  final Object? arguments;
  final Uri uri;

  const DeepLinkResolution({
    required this.routeName,
    required this.uri,
    this.arguments,
  });
}

class GigvoraDeepLinkRouter {
  final GigvoraNavigationConfig navigation;
  final Map<String, WidgetBuilder> addonRoutes;

  GigvoraDeepLinkRouter({
    required this.navigation,
    this.addonRoutes = const {},
  });

  DeepLinkResolution? resolve(Uri uri) {
    final normalizedPath = _normalize(uri.path);
    final availableRoutes = _availableRoutes();

    final navMatch = _matchNavigationRoute(normalizedPath, availableRoutes);
    if (navMatch != null) {
      return DeepLinkResolution(routeName: navMatch, uri: uri, arguments: uri.queryParameters);
    }

    final parameterizedMatch = _matchParameterized(normalizedPath, uri, availableRoutes);
    if (parameterizedMatch != null) {
      return parameterizedMatch;
    }

    final dynamicMatch = _resolveDynamic(uri, availableRoutes);
    if (dynamicMatch != null) {
      return dynamicMatch;
    }

    return null;
  }

  List<String> _availableRoutes() {
    final routes = <String>{
      ...navigation.primary.map((item) => _normalize(item.route)),
      ...navigation.secondary.map((item) => _normalize(item.route)),
      ...navigation.admin.map((item) => _normalize(item.route)),
      ...navigation.settings.map((item) => _normalize(item.route)),
      ...navigation.mobile.tabs.map((item) => _normalize(item.route)),
      ...navigation.mobile.drawer.expand((section) => section.items.map((item) => _normalize(item.route))),
      ...navigation.groups
          .expand((group) => group.items)
          .map((item) => _normalize(item.route)),
      ...addonRoutes.keys.map(_normalize),
    };

    return routes.where((route) => route.isNotEmpty).toList();
  }

  String? _matchNavigationRoute(String path, List<String> availableRoutes) {
    final candidates = availableRoutes
        .where((route) => path == route || path == _stripTrailingSlash(route))
        .toList();
    if (candidates.isNotEmpty) {
      return candidates.first;
    }

    final partial = availableRoutes
        .where((route) => route != '/' && path.startsWith(route))
        .toList();
    return partial.isNotEmpty ? partial.first : null;
  }

  DeepLinkResolution? _matchParameterized(String path, Uri uri, List<String> availableRoutes) {
    final pathSegments = path.split('/')..removeWhere((segment) => segment.isEmpty);

    for (final route in availableRoutes.where((route) => route.contains(':'))) {
      final routeSegments = route.split('/')..removeWhere((segment) => segment.isEmpty);
      if (routeSegments.length != pathSegments.length) continue;

      final arguments = {...uri.queryParameters};
      var matched = true;

      for (var i = 0; i < routeSegments.length; i++) {
        final pattern = routeSegments[i];
        final value = pathSegments[i];

        if (pattern.startsWith(':')) {
          arguments[pattern.substring(1)] = value;
        } else if (pattern != value) {
          matched = false;
          break;
        }
      }

      if (matched) {
        return DeepLinkResolution(routeName: route, uri: uri, arguments: arguments);
      }
    }

    return null;
  }

  DeepLinkResolution? _resolveDynamic(Uri uri, List<String> availableRoutes) {
    final segments = uri.pathSegments;
    if (segments.isEmpty) {
      final home = _matchNavigationRoute('/', availableRoutes);
      return home != null ? DeepLinkResolution(routeName: home, uri: uri) : null;
    }

    switch (segments.first) {
      case 'profile':
        final profileRoute = _matchNavigationRoute('/profile', availableRoutes);
        return profileRoute != null
            ? DeepLinkResolution(
                routeName: profileRoute,
                uri: uri,
                arguments: {'userId': segments.length > 1 ? segments[1] : null, ...uri.queryParameters},
              )
            : null;
      case 'jobs':
        final detailRoute = availableRoutes.firstWhere(
          (route) => route.contains('/jobs/:id'),
          orElse: () => '',
        );
        if (detailRoute.isNotEmpty && segments.length > 1) {
          return DeepLinkResolution(
            routeName: detailRoute,
            uri: uri,
            arguments: {'id': segments[1], ...uri.queryParameters},
          );
        }
        final listRoute = _matchNavigationRoute('/jobs', availableRoutes);
        if (listRoute != null) {
          return DeepLinkResolution(routeName: listRoute, uri: uri, arguments: uri.queryParameters);
        }
        break;
      case 'freelance':
        final detailRoute = availableRoutes.firstWhere(
          (route) => route.contains('/freelance/:id'),
          orElse: () => '',
        );
        if (detailRoute.isNotEmpty && segments.length > 1) {
          return DeepLinkResolution(
            routeName: detailRoute,
            uri: uri,
            arguments: {'id': segments[1], ...uri.queryParameters},
          );
        }
        final listRoute = _matchNavigationRoute('/freelance', availableRoutes);
        if (listRoute != null) {
          return DeepLinkResolution(routeName: listRoute, uri: uri, arguments: uri.queryParameters);
        }
        break;
      case 'events':
      case 'live':
        final liveRoute = availableRoutes.firstWhere(
          (route) => route.startsWith('/events') || route.startsWith('/live'),
          orElse: () => '',
        );
        if (liveRoute.isNotEmpty) {
          return DeepLinkResolution(routeName: liveRoute, uri: uri, arguments: uri.queryParameters);
        }
        break;
      case 'posts':
      case 'post':
        final feedRoute = _matchNavigationRoute('/', availableRoutes) ?? _matchNavigationRoute('/feed', availableRoutes);
        if (feedRoute != null) {
          return DeepLinkResolution(
            routeName: feedRoute,
            uri: uri,
            arguments: {'postId': segments.length > 1 ? segments[1] : null, ...uri.queryParameters},
          );
        }
        break;
      case 'videos':
      case 'reels':
      case 'stories':
        final mediaRoute = availableRoutes.firstWhere(
          (route) => route.contains('video') || route.contains('story') || route.contains('reel'),
          orElse: () => '',
        );
        if (mediaRoute.isNotEmpty) {
          return DeepLinkResolution(routeName: mediaRoute, uri: uri, arguments: uri.queryParameters);
        }
        final feedRoute = _matchNavigationRoute('/', availableRoutes);
        if (feedRoute != null) {
          return DeepLinkResolution(routeName: feedRoute, uri: uri, arguments: uri.queryParameters);
        }
        break;
      default:
        break;
    }

    return null;
  }

  String _normalize(String path) {
    if (path.isEmpty) return '';
    return path.startsWith('/') ? path : '/$path';
  }

  String _stripTrailingSlash(String value) {
    if (value.endsWith('/') && value.length > 1) {
      return value.substring(0, value.length - 1);
    }
    return value;
  }
}

