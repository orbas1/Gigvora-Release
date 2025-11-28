import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../state/ads_blocs.dart';

class AnalyticsPage extends StatefulWidget {
  const AnalyticsPage({super.key});

  static Widget builder(BuildContext context) => const AnalyticsPage();

  @override
  State<AnalyticsPage> createState() => _AnalyticsPageState();
}

class _AnalyticsPageState extends State<AnalyticsPage> {
  DateTimeRange? _range;

  @override
  void initState() {
    super.initState();
    _range = DateTimeRange(start: DateTime.now().subtract(const Duration(days: 7)), end: DateTime.now());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Analytics')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Row(
              children: [
                TextButton(
                  onPressed: () async {
                    final picked = await showDateRangePicker(
                      context: context,
                      firstDate: DateTime.now().subtract(const Duration(days: 365)),
                      lastDate: DateTime.now(),
                      initialDateRange: _range,
                    );
                    if (picked != null) {
                      setState(() => _range = picked);
                    }
                  },
                  child: Text(_range == null
                      ? 'Select range'
                      : '${_range!.start.toLocal().toShortDateString()} - ${_range!.end.toLocal().toShortDateString()}'),
                ),
                const Spacer(),
                ElevatedButton(
                  onPressed: () {
                    final campaignState = context.read<CampaignBloc>().state;
                    if (campaignState.campaigns.isEmpty || _range == null) return;
                    context.read<AnalyticsBloc>().load(
                          campaignId: campaignState.campaigns.first.id,
                          start: _range!.start,
                          end: _range!.end,
                        );
                  },
                  child: const Text('Fetch Metrics'),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Expanded(
              child: BlocBuilder<AnalyticsBloc, AnalyticsState>(
                builder: (context, state) {
                  if (state.status == AnalyticsStatus.loading) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  if (state.status == AnalyticsStatus.error) {
                    return Center(child: Text(state.error ?? 'Error'));
                  }
                  if (state.metrics.isEmpty) {
                    return const Center(child: Text('No metrics yet'));
                  }
                  return ListView(
                    children: state.metrics
                        .map(
                          (metric) => ListTile(
                            title: Text('Campaign ${metric.campaignId}'),
                            subtitle: Text(
                              'Impr: ${metric.impressions} · Clicks: ${metric.clicks} · Conv: ${metric.conversions}',
                            ),
                            trailing: Column(
                              crossAxisAlignment: CrossAxisAlignment.end,
                              children: [
                                Text('CTR: ${metric.ctr.toStringAsFixed(2)}%'),
                                Text('CPC: \\$${metric.cpc.toStringAsFixed(2)}'),
                                Text('CPA: \\$${metric.cpa.toStringAsFixed(2)}'),
                                Text('CPM: \\$${metric.cpm.toStringAsFixed(2)}'),
                              ],
                            ),
                          ),
                        )
                        .toList(),
                  );
                },
              ),
            )
          ],
        ),
      ),
    );
  }
}

extension on DateTime {
  String toShortDateString() => '${day.toString().padLeft(2, '0')}/${month.toString().padLeft(2, '0')}/${year.toString()}';
}
