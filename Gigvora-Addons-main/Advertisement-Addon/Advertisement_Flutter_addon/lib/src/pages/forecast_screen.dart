import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../services/ads_service.dart';
import '../state/forecast_state.dart';

class ForecastScreen extends StatefulWidget {
  const ForecastScreen({super.key, this.campaignId = 1});

  static const routeName = '/ads/forecast';

  final int campaignId;

  @override
  State<ForecastScreen> createState() => _ForecastScreenState();
}

class _ForecastScreenState extends State<ForecastScreen> {
  double budget = 100;
  double duration = 7;

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => ForecastCubit(RepositoryProvider.of<AdsService>(context)),
      child: Scaffold(
        appBar: AppBar(title: const Text('Forecast')),
        body: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Daily Budget: ${budget.toStringAsFixed(0)}'),
              Slider(
                value: budget,
                min: 10,
                max: 1000,
                onChanged: (value) => setState(() => budget = value),
                onChangeEnd: (value) => context
                    .read<ForecastCubit>()
                    .fetch(widget.campaignId, {'budget': value, 'duration': duration}),
              ),
              Text('Duration (days): ${duration.toStringAsFixed(0)}'),
              Slider(
                value: duration,
                min: 1,
                max: 60,
                onChanged: (value) => setState(() => duration = value),
                onChangeEnd: (value) => context
                    .read<ForecastCubit>()
                    .fetch(widget.campaignId, {'budget': budget, 'duration': value}),
              ),
              const SizedBox(height: 16),
              BlocBuilder<ForecastCubit, ForecastState>(
                builder: (context, state) {
                  if (state.status == ForecastStatus.loading) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  final forecast = state.forecast;
                  return Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Wrap(
                          spacing: 12,
                          runSpacing: 12,
                          children: [
                            _MetricCard(
                                label: 'Impressions',
                                value: forecast?.estimatedImpressions.toString() ?? '--'),
                            _MetricCard(
                                label: 'Clicks',
                                value: forecast?.estimatedClicks.toString() ?? '--'),
                            _MetricCard(
                                label: 'Conversions',
                                value: forecast?.estimatedConversions.toString() ?? '--'),
                            _MetricCard(
                                label: 'Estimated Spend',
                                value: forecast != null ? '\$${forecast.estimatedSpend.toStringAsFixed(2)}' : '--'),
                          ],
                        ),
                        const Spacer(),
                        ElevatedButton(
                          onPressed: () => ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Forecast applied to campaign')),
                          ),
                          child: const Text('Apply these settings'),
                        ),
                      ],
                    ),
                  );
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _MetricCard extends StatelessWidget {
  const _MetricCard({required this.label, required this.value});
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: Theme.of(context).textTheme.labelMedium),
            const SizedBox(height: 4),
            Text(value, style: Theme.of(context).textTheme.titleLarge),
          ],
        ),
      ),
    );
  }
}
