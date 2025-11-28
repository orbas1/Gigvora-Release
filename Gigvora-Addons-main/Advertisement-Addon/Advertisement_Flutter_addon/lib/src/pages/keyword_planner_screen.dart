import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../services/ads_service.dart';
import '../state/keyword_planner_state.dart';

class KeywordPlannerScreen extends StatelessWidget {
  const KeywordPlannerScreen({super.key});

  static const routeName = '/ads/keyword-planner';

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => KeywordPlannerCubit(RepositoryProvider.of<AdsService>(context)),
      child: Scaffold(
        appBar: AppBar(title: const Text('Keyword Planner')),
        body: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              TextField(
                decoration: const InputDecoration(
                  hintText: 'Enter keyword or URL',
                  prefixIcon: Icon(Icons.search),
                ),
                onChanged: (value) => context.read<KeywordPlannerCubit>().search(value),
              ),
              const SizedBox(height: 12),
              Expanded(
                child: BlocBuilder<KeywordPlannerCubit, KeywordPlannerState>(
                  builder: (context, state) {
                    if (state.status == KeywordPlannerStatus.loading) {
                      return const Center(child: CircularProgressIndicator());
                    }
                    if (state.results.isEmpty) {
                      return const Center(child: Text('Search to view keyword ideas'));
                    }
                    return ListView.separated(
                      itemCount: state.results.length,
                      separatorBuilder: (_, __) => const Divider(),
                      itemBuilder: (context, index) {
                        final keyword = state.results[index];
                        return ListTile(
                          leading: Checkbox(value: false, onChanged: (_) {}),
                          title: Text(keyword.keyword),
                          subtitle: Text('CPC: ${keyword.cpc} • Volume: ${keyword.volume}'),
                        );
                      },
                    );
                  },
                ),
              ),
              ElevatedButton(
                onPressed: () => ScaffoldMessenger.of(context)
                    .showSnackBar(const SnackBar(content: Text('Keywords added to campaign'))),
                child: const Text('Add to Campaign'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
