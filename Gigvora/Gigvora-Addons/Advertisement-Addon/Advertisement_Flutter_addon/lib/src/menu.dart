import 'package:flutter/material.dart';

import 'models/models.dart';
import 'pages/ads_home_screen.dart';
import 'pages/ads_reports_screen.dart';
import 'pages/campaign_detail_screen.dart';
import 'pages/campaign_list_screen.dart';
import 'pages/campaign_wizard_screen.dart';
import 'pages/creative_edit_screen.dart';
import 'pages/creative_list_screen.dart';
import 'pages/forecast_screen.dart';
import 'pages/keyword_planner_screen.dart';

class MenuItem {
  const MenuItem({required this.title, required this.icon, required this.route});

  final String title;
  final IconData icon;
  final String route;
}

final List<MenuItem> adsMenuItems = [
  const MenuItem(title: 'Ads Manager', icon: Icons.campaign_outlined, route: AdsHomeScreen.routeName),
  const MenuItem(title: 'Campaigns', icon: Icons.list_alt_outlined, route: CampaignListScreen.routeName),
  const MenuItem(title: 'Keyword Planner', icon: Icons.search, route: KeywordPlannerScreen.routeName),
  const MenuItem(title: 'Forecast', icon: Icons.trending_up, route: ForecastScreen.routeName),
  const MenuItem(title: 'Reports', icon: Icons.analytics_outlined, route: AdsReportsScreen.routeName),
];

Map<String, WidgetBuilder> buildAdsRoutes() {
  return {
    AdsHomeScreen.routeName: (_) => const AdsHomeScreen(),
    CampaignListScreen.routeName: (_) => const CampaignListScreen(),
    CampaignWizardScreen.routeName: (_) => const CampaignWizardScreen(),
    CreativeListScreen.routeName: (_) => const CreativeListScreen(),
    CreativeEditScreen.routeName: (_) => const CreativeEditScreen(),
    KeywordPlannerScreen.routeName: (_) => const KeywordPlannerScreen(),
    ForecastScreen.routeName: (_) => const ForecastScreen(),
    AdsReportsScreen.routeName: (_) => const AdsReportsScreen(),
    '/ads/campaigns/:id': (context) {
      final args = ModalRoute.of(context)?.settings.arguments;
      if (args is Campaign) {
        return CampaignDetailScreen(campaign: args);
      }
      return const Scaffold(body: Center(child: Text('Campaign not provided')));
    },
  };
}
