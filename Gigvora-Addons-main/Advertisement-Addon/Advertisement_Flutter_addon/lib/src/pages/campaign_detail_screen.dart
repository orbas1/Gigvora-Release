import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../services/ads_service.dart';
import '../state/campaign_detail_state.dart';

class CampaignDetailScreen extends StatelessWidget {
  const CampaignDetailScreen({super.key, required this.campaign});

  static String routeName(int id) => '/ads/campaigns/$id';

  final Campaign campaign;

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => CampaignDetailCubit(RepositoryProvider.of<AdsService>(context))..load(campaign),
      child: DefaultTabController(
        length: 5,
        child: Scaffold(
          appBar: AppBar(
            title: Text(campaign.name),
            actions: [
              IconButton(
                icon: const Icon(Icons.pause_circle),
                onPressed: () {},
              ),
              IconButton(
                icon: const Icon(Icons.edit),
                onPressed: () => Navigator.of(context).pushNamed('/ads/campaigns/create', arguments: campaign),
              ),
            ],
            bottom: const TabBar(
              isScrollable: true,
              tabs: [
                Tab(text: 'Overview'),
                Tab(text: 'Ads'),
                Tab(text: 'Targeting'),
                Tab(text: 'Budget'),
                Tab(text: 'Performance'),
              ],
            ),
          ),
          body: BlocBuilder<CampaignDetailCubit, CampaignDetailState>(
            builder: (context, state) {
              if (state.status == CampaignDetailStatus.loading) {
                return const Center(child: CircularProgressIndicator());
              }
              return TabBarView(
                children: [
                  _OverviewTab(campaign: campaign),
                  _AdsTab(campaign: campaign),
                  _TargetingTab(campaign: campaign),
                  _BudgetTab(campaign: campaign),
                  _PerformanceTab(metrics: state.metrics),
                ],
              );
            },
          ),
        ),
      ),
    );
  }
}

class _OverviewTab extends StatelessWidget {
  const _OverviewTab({required this.campaign});
  final Campaign campaign;

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Text('Objective: ${campaign.objective}', style: Theme.of(context).textTheme.titleMedium),
        const SizedBox(height: 12),
        Text('Status: ${campaign.status}'),
        const SizedBox(height: 12),
        Text('Schedule: ${campaign.startDate.toLocal()} - ${campaign.endDate.toLocal()}'),
      ],
    );
  }
}

class _AdsTab extends StatelessWidget {
  const _AdsTab({required this.campaign});
  final Campaign campaign;

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        ElevatedButton(
          onPressed: () => Navigator.of(context).pushNamed('/ads/creatives', arguments: campaign),
          child: const Text('Manage Creatives'),
        ),
        const SizedBox(height: 12),
        ...campaign.adGroups
            .expand((group) => group.creatives)
            .map((creative) => Card(
                  child: ListTile(
                    title: Text(creative.headline),
                    subtitle: Text(creative.description),
                    trailing: Chip(label: Text(creative.status)),
                  ),
                )),
      ],
    );
  }
}

class _TargetingTab extends StatelessWidget {
  const _TargetingTab({required this.campaign});
  final Campaign campaign;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: const [
          Text('Targeting details will appear here.'),
          SizedBox(height: 8),
          Text('Include gender, locations, interests, and keywords.'),
        ],
      ),
    );
  }
}

class _BudgetTab extends StatelessWidget {
  const _BudgetTab({required this.campaign});
  final Campaign campaign;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Daily Budget: ${campaign.dailyBudget.toStringAsFixed(2)}'),
          Text('Lifetime Budget: ${campaign.lifetimeBudget.toStringAsFixed(2)}'),
          Text('Dates: ${campaign.startDate.toLocal()} - ${campaign.endDate.toLocal()}'),
        ],
      ),
    );
  }
}

class _PerformanceTab extends StatelessWidget {
  const _PerformanceTab({required this.metrics});
  final List<Metric> metrics;

  @override
  Widget build(BuildContext context) {
    if (metrics.isEmpty) {
      return const Center(child: Text('No performance data'));
    }
    return ListView.separated(
      padding: const EdgeInsets.all(16),
      itemBuilder: (context, index) {
        final metric = metrics[index];
        return ListTile(
          title: Text(metric.placement),
          subtitle: Text('Impressions: ${metric.impressions} • Clicks: ${metric.clicks}'),
          trailing: Text('Spend: ${metric.spend.toStringAsFixed(2)}'),
        );
      },
      separatorBuilder: (_, __) => const Divider(),
      itemCount: metrics.length,
    );
  }
}
