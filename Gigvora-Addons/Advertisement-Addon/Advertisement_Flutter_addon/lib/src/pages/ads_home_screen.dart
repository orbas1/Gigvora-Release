import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../services/ads_service.dart';
import '../state/ads_home_state.dart';
import '../widgets/ads_cards.dart';
import 'forecast_screen.dart';
import 'keyword_planner_screen.dart';

class AdsHomeScreen extends StatelessWidget {
  const AdsHomeScreen({super.key});

  static const routeName = '/ads/home';

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => AdsHomeCubit(RepositoryProvider.of<AdsService>(context))..load(),
      child: Scaffold(
        appBar: AppBar(
          title: const Text('Ads Manager'),
          actions: [
            IconButton(
              icon: const Icon(Icons.refresh),
              onPressed: () => context.read<AdsHomeCubit>().load(),
            ),
          ],
        ),
        floatingActionButton: FloatingActionButton(
          onPressed: () => Navigator.of(context).pushNamed('/ads/campaigns/create'),
          child: const Icon(Icons.add),
        ),
        body: BlocBuilder<AdsHomeCubit, AdsHomeState>(
          builder: (context, state) {
            if (state.status == AdsHomeStatus.loading) {
              return const Center(child: CircularProgressIndicator());
            }
            if (state.status == AdsHomeStatus.error) {
              return Center(child: Text(state.error ?? 'Something went wrong'));
            }
            final theme = Theme.of(context);
            return RefreshIndicator(
              onRefresh: () => context.read<AdsHomeCubit>().load(),
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  _HeroHeader(
                    primaryValue: state.metrics['spend']?.toString() ?? '--',
                    description: 'Total spend (last 30 days)',
                    onPlannerTap: () => Navigator.of(context).pushNamed(KeywordPlannerScreen.routeName),
                    onForecastTap: () => Navigator.of(context).pushNamed(ForecastScreen.routeName),
                  ),
                  const SizedBox(height: 16),
                  _MetricsGrid(metrics: state.metrics),
                  const SizedBox(height: 24),
                  Text('Top Campaigns', style: theme.textTheme.titleLarge),
                  const SizedBox(height: 8),
                  ...state.campaigns.take(3).map(
                    (c) => CampaignListTile(
                      title: c.name,
                      status: c.status,
                      subtitle: '${c.objective} • ${c.dailyBudget.toStringAsFixed(2)}',
                      onTap: () => Navigator.of(context).pushNamed('/ads/campaigns/${c.id}', arguments: c),
                    ),
                  ),
                  const SizedBox(height: 16),
                  FilledButton(
                    onPressed: () => Navigator.of(context).pushNamed('/ads/campaigns'),
                    child: const Text('View Campaigns'),
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}

class _HeroHeader extends StatelessWidget {
  const _HeroHeader({
    required this.primaryValue,
    required this.description,
    required this.onPlannerTap,
    required this.onForecastTap,
  });

  final String primaryValue;
  final String description;
  final VoidCallback onPlannerTap;
  final VoidCallback onForecastTap;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(20),
        gradient: LinearGradient(
          colors: [
            theme.colorScheme.primary.withOpacity(0.1),
            theme.colorScheme.secondary.withOpacity(0.1),
          ],
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Spend this month', style: theme.textTheme.labelMedium),
          const SizedBox(height: 4),
          Text(primaryValue, style: theme.textTheme.headlineMedium?.copyWith(fontWeight: FontWeight.w700)),
          const SizedBox(height: 4),
          Text(description, style: theme.textTheme.bodySmall),
          const SizedBox(height: 16),
          Wrap(
            spacing: 12,
            runSpacing: 12,
            children: [
              FilledButton.icon(
                onPressed: onPlannerTap,
                icon: const Icon(Icons.search),
                label: const Text('Keyword planner'),
              ),
              OutlinedButton.icon(
                onPressed: onForecastTap,
                icon: const Icon(Icons.trending_up),
                label: const Text('Forecast'),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _MetricsGrid extends StatelessWidget {
  const _MetricsGrid({required this.metrics});

  final Map<String, dynamic> metrics;

  @override
  Widget build(BuildContext context) {
    final items = [
      _MetricTile('Impressions', metrics['impressions']?.toString() ?? '--', Icons.visibility_outlined),
      _MetricTile('Clicks', metrics['clicks']?.toString() ?? '--', Icons.touch_app_outlined),
      _MetricTile('Conversions', metrics['conversions']?.toString() ?? '--', Icons.flag_outlined),
      _MetricTile('CPC', metrics['cpc']?.toString() ?? '--', Icons.price_change_outlined),
    ];
    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        crossAxisSpacing: 12,
        mainAxisSpacing: 12,
        childAspectRatio: 3,
      ),
      itemCount: items.length,
      itemBuilder: (context, index) {
        final entry = items[index];
        return Card(
          elevation: 0,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
            side: BorderSide(color: Theme.of(context).dividerColor),
          ),
          child: Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                CircleAvatar(
                  backgroundColor: Theme.of(context).colorScheme.primary.withOpacity(0.1),
                  child: Icon(entry.icon, color: Theme.of(context).colorScheme.primary),
                ),
                const SizedBox(width: 12),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(entry.label, style: Theme.of(context).textTheme.labelMedium),
                    Text(entry.value, style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w600)),
                  ],
                )
              ],
            ),
          ),
        );
      },
    );
  }
}

class _MetricTile {
  const _MetricTile(this.label, this.value, this.icon);

  final String label;
  final String value;
  final IconData icon;
}
