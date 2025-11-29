import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../state/ads_blocs.dart';

class ForecastPage extends StatefulWidget {
  const ForecastPage({super.key});

  static Widget builder(BuildContext context) => const ForecastPage();

  @override
  State<ForecastPage> createState() => _ForecastPageState();
}

class _ForecastPageState extends State<ForecastPage> {
  double _dailyBudget = 25;
  int _duration = 7;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Forecast & Simulation')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Slider(
              label: 'Daily Budget: \\$${_dailyBudget.toStringAsFixed(0)}',
              value: _dailyBudget,
              min: 5,
              max: 500,
              divisions: 99,
              onChanged: (value) => setState(() => _dailyBudget = value),
            ),
            Slider(
              label: 'Duration (days): $_duration',
              value: _duration.toDouble(),
              min: 1,
              max: 90,
              divisions: 89,
              onChanged: (value) => setState(() => _duration = value.toInt()),
            ),
            ElevatedButton(
              onPressed: () {
                final campaignState = context.read<CampaignBloc>().state;
                if (campaignState.campaigns.isEmpty) return;
                final draft = Forecast(
                  campaignId: campaignState.campaigns.first.id,
                  estimatedImpressions: 0,
                  estimatedClicks: 0,
                  estimatedConversions: 0,
                  estimatedSpend: _dailyBudget * _duration,
                );
                context.read<ForecastBloc>().run(draft);
              },
              child: const Text('Run Forecast'),
            ),
            const SizedBox(height: 16),
            Expanded(
              child: BlocBuilder<ForecastBloc, ForecastState>(
                builder: (context, state) {
                  if (state.status == ForecastStatus.loading) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  if (state.status == ForecastStatus.error) {
                    return Center(child: Text(state.error ?? 'Error'));
                  }
                  if (state.forecast == null) {
                    return const Center(child: Text('Run a forecast to see results'));
                  }
                  final forecast = state.forecast!;
                  return Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Campaign ${forecast.campaignId}', style: Theme.of(context).textTheme.titleMedium),
                          Text('Estimated Impressions: ${forecast.estimatedImpressions}'),
                          Text('Estimated Clicks: ${forecast.estimatedClicks}'),
                          Text('Estimated Conversions: ${forecast.estimatedConversions}'),
                          Text('Estimated Spend: \\$${forecast.estimatedSpend.toStringAsFixed(2)}'),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}
