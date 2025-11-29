import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../services/ads_service.dart';
import '../state/campaign_list_state.dart';
import '../widgets/ads_cards.dart';

class CampaignListScreen extends StatelessWidget {
  const CampaignListScreen({super.key});

  static const routeName = '/ads/campaigns';

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => CampaignListCubit(RepositoryProvider.of<AdsService>(context))..load(),
      child: Scaffold(
        appBar: AppBar(title: const Text('Campaigns')),
        floatingActionButton: FloatingActionButton(
          onPressed: () => Navigator.of(context).pushNamed('/ads/campaigns/create'),
          child: const Icon(Icons.add),
        ),
        body: SafeArea(
          child: Column(
            children: [
              Padding(
                padding: const EdgeInsets.all(16),
                child: TextField(
                  decoration: const InputDecoration(
                    prefixIcon: Icon(Icons.search),
                    hintText: 'Search campaigns',
                  ),
                  onChanged: (value) => context.read<CampaignListCubit>().load(search: value),
                ),
              ),
              SizedBox(
                height: 48,
                child: ListView(
                  scrollDirection: Axis.horizontal,
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  children: const [
                    _StatusChip(label: 'Active'),
                    _StatusChip(label: 'Paused'),
                    _StatusChip(label: 'Draft'),
                  ],
                ),
              ),
              Expanded(
                child: BlocBuilder<CampaignListCubit, CampaignListState>(
                  builder: (context, state) {
                    if (state.status == CampaignListStatus.loading) {
                      return const Center(child: CircularProgressIndicator());
                    }
                    if (state.campaigns.isEmpty) {
                      return const Center(child: Text('No campaigns found'));
                    }
                    return RefreshIndicator(
                      onRefresh: () => context.read<CampaignListCubit>().load(search: state.filter),
                      child: ListView.builder(
                        padding: const EdgeInsets.all(16),
                        itemCount: state.campaigns.length,
                        itemBuilder: (context, index) {
                          final campaign = state.campaigns[index];
                          return CampaignListTile(
                            title: campaign.name,
                            status: campaign.status,
                            subtitle: '${campaign.objective} • ${campaign.startDate.toLocal().toShortDateString()} - ${campaign.endDate.toLocal().toShortDateString()}',
                            onTap: () => Navigator.of(context).pushNamed('/ads/campaigns/${campaign.id}', arguments: campaign),
                          );
                        },
                      ),
                    );
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _StatusChip extends StatelessWidget {
  const _StatusChip({required this.label});
  final String label;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: FilterChip(
        label: Text(label),
        onSelected: (_) => context.read<CampaignListCubit>().load(search: context.read<CampaignListCubit>().state.filter),
      ),
    );
  }
}

extension on DateTime {
  String toShortDateString() {
    return '${month.toString().padLeft(2, '0')}/${day.toString().padLeft(2, '0')}';
  }
}
