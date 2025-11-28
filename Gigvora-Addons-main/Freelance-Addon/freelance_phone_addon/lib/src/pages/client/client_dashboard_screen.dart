import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../state/dashboard_provider.dart';
import '../../widgets/metric_grid.dart';
import '../../widgets/project_card.dart';

class ClientDashboardScreen extends ConsumerWidget {
  const ClientDashboardScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final snapshot = ref.watch(dashboardSnapshotProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Client Dashboard')),
      body: snapshot.when(
        data: (data) => SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              MetricGrid(metrics: [
                MetricItem(label: 'Open projects', value: data.projectCount.toString()),
                MetricItem(label: 'Published gigs', value: data.gigCount.toString()),
                MetricItem(label: 'Disputes', value: data.disputeCount.toString()),
                MetricItem(label: 'Escrow items', value: data.escrows.length.toString()),
              ]),
              const SizedBox(height: 16),
              Text('Recent escrow', style: Theme.of(context).textTheme.titleMedium),
              ...data.escrows.take(3).map((escrow) => ListTile(
                    leading: const Icon(Icons.account_balance_wallet_outlined),
                    title: Text('Escrow #${escrow.id}'),
                    subtitle: Text('${escrow.status} · ${escrow.currency} ${escrow.amount.toStringAsFixed(2)}'),
                  )),
              const SizedBox(height: 16),
              Text('Projects in focus', style: Theme.of(context).textTheme.titleMedium),
              ...data.recommendedProjects
                  .map((project) => ProjectCard(project: project, onTap: () => Navigator.pushNamed(
                        context,
                        '/freelance/freelancer/project',
                        arguments: project,
                      )))
                  .toList(),
            ],
          ),
        ),
        error: (error, _) => Center(child: Text('Unable to load dashboard: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}
