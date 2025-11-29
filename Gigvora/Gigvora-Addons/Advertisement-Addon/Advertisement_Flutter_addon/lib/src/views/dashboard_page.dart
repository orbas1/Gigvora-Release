import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../menu.dart';
import '../repository/advertisement_repository.dart';
import '../state/ads_blocs.dart';

class AdsDashboardPage extends StatelessWidget {
  const AdsDashboardPage({super.key, this.menu = defaultAdsMenu});

  static Widget builder(BuildContext context) => const AdsDashboardPage();

  final List<AdsMenuItem> menu;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Ads Dashboard')),
      body: ListView(
        children: [
          const _Headline(),
          Wrap(
            spacing: 12,
            runSpacing: 12,
            children: menu
                .map(
                  (item) => ActionChip(
                    label: Text(item.title),
                    avatar: Icon(item.icon),
                    onPressed: () => Navigator.of(context).push(
                      MaterialPageRoute(builder: item.builder),
                    ),
                  ),
                )
                .toList(),
          ),
          const SizedBox(height: 24),
          const _CampaignPreview(),
        ],
      ),
    );
  }
}

class _Headline extends StatelessWidget {
  const _Headline();

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: const [
          Text('Run high-performing campaigns', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
          SizedBox(height: 8),
          Text('Create, manage, and forecast ads across feed, search, gigs, jobs, podcasts, webinars, and networking placements.'),
        ],
      ),
    );
  }
}

class _CampaignPreview extends StatelessWidget {
  const _CampaignPreview();

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.all(16),
      child: BlocBuilder<CampaignBloc, CampaignState>(
        builder: (context, state) {
          switch (state.status) {
            case CampaignStatus.loading:
              return const Padding(
                padding: EdgeInsets.all(16),
                child: Center(child: CircularProgressIndicator()),
              );
            case CampaignStatus.error:
              return Padding(
                padding: const EdgeInsets.all(16),
                child: Text(state.error ?? 'Unable to load campaigns'),
              );
            case CampaignStatus.ready:
              final repository = context.read<AdvertisementRepository>();
              final spendByPlacement = repository.aggregateSpendByPlacement(state.campaigns);
              return Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Active Campaigns', style: TextStyle(fontWeight: FontWeight.bold)),
                    for (final campaign in state.campaigns)
                      ListTile(
                        leading: const Icon(Icons.campaign),
                        title: Text(campaign.name),
                        subtitle: Text('${campaign.objective} · ${campaign.status}'),
                        trailing: Text('Daily \\$${campaign.dailyBudget.toStringAsFixed(2)}'),
                      ),
                    const SizedBox(height: 12),
                    const Text('Bid Spend By Placement', style: TextStyle(fontWeight: FontWeight.bold)),
                    Wrap(
                      spacing: 8,
                      children: spendByPlacement.entries
                          .map((entry) => Chip(label: Text('${entry.key}: ${entry.value.toStringAsFixed(2)}')))
                          .toList(),
                    ),
                  ],
                ),
              );
          }
        },
      ),
    );
  }
}
