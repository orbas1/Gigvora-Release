import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../services/ads_service.dart';
import '../state/ads_home_state.dart';
import '../widgets/ads_cards.dart';

class AdsHomeScreen extends StatelessWidget {
  const AdsHomeScreen({super.key});

  static const routeName = '/ads/home';

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => AdsHomeCubit(RepositoryProvider.of<AdsService>(context))..load(),
      child: Scaffold(
        appBar: AppBar(title: const Text('Ads Manager')),
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
            return RefreshIndicator(
              onRefresh: () => context.read<AdsHomeCubit>().load(),
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  Wrap(
                    spacing: 12,
                    runSpacing: 12,
                    children: [
                      KpiCard(label: 'Spend', value: state.metrics['spend']?.toString() ?? '--'),
                      KpiCard(label: 'Impressions', value: state.metrics['impressions']?.toString() ?? '--'),
                      KpiCard(label: 'Clicks', value: state.metrics['clicks']?.toString() ?? '--'),
                      KpiCard(label: 'Conversions', value: state.metrics['conversions']?.toString() ?? '--'),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Text('Top Campaigns', style: Theme.of(context).textTheme.titleLarge),
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
