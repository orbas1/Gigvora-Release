import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../state/ads_blocs.dart';

class KeywordPlannerPage extends StatefulWidget {
  const KeywordPlannerPage({super.key});

  static Widget builder(BuildContext context) => const KeywordPlannerPage();

  @override
  State<KeywordPlannerPage> createState() => _KeywordPlannerPageState();
}

class _KeywordPlannerPageState extends State<KeywordPlannerPage> {
  final TextEditingController _keyword = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Keyword Planner')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _keyword,
              decoration: const InputDecoration(labelText: 'Keyword', hintText: 'e.g. webinars, jobs, gigs'),
            ),
            const SizedBox(height: 8),
            ElevatedButton(
              onPressed: () => context.read<KeywordPlannerBloc>().search(_keyword.text),
              child: const Text('Get Pricing'),
            ),
            const SizedBox(height: 16),
            Expanded(
              child: BlocBuilder<KeywordPlannerBloc, KeywordPlannerState>(
                builder: (context, state) {
                  if (state.status == KeywordPlannerStatus.loading) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  if (state.status == KeywordPlannerStatus.error) {
                    return Center(child: Text(state.error ?? 'Error'));
                  }
                  if (state.prices.isEmpty) {
                    return const Center(child: Text('No pricing data yet'));
                  }
                  return ListView(
                    children: state.prices
                        .map(
                          (price) => ListTile(
                            title: Text(price.keyword),
                            subtitle: Text('CPA: \\$${price.cpa.toStringAsFixed(2)} · CPC: \\$${price.cpc.toStringAsFixed(2)}'),
                            trailing: Text('CPM: \\$${price.cpm.toStringAsFixed(2)}'),
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
