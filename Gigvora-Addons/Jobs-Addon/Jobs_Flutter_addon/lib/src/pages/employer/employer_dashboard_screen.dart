import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../services/api_client.dart';
import '../../services/employer_service.dart';
import '../../state/employer_state.dart';

class EmployerDashboardScreen extends StatelessWidget {
  const EmployerDashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => EmployerState(
        EmployerService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      )
        ..loadDashboard()
        ..loadJobs(),
      child: Scaffold(
        appBar: AppBar(title: const Text('Employer Dashboard')),
        body: Consumer<EmployerState>(
          builder: (context, state, _) {
            final cards = state.kpis.entries.toList();
            return ListView(
              padding: const EdgeInsets.all(16),
              children: [
                GridView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: cards.length,
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 2, childAspectRatio: 2.5),
                  itemBuilder: (context, index) {
                    final entry = cards[index];
                    return Card(
                      child: Padding(
                        padding: const EdgeInsets.all(12),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(entry.key.toUpperCase(), style: Theme.of(context).textTheme.labelLarge),
                            Text('${entry.value}', style: Theme.of(context).textTheme.headlineSmall),
                          ],
                        ),
                      ),
                    );
                  },
                ),
                const SizedBox(height: 16),
                ListTile(
                  title: const Text('Recent jobs'),
                  trailing: TextButton(
                    onPressed: () => Navigator.pushNamed(context, '/employer/jobs'),
                    child: const Text('Manage'),
                  ),
                ),
                ...state.jobs.map((job) => ListTile(
                      title: Text(job.title),
                      subtitle: Text(job.status ?? 'open'),
                      trailing: Text('${job.applicationsCount ?? 0} apps'),
                    )),
              ],
            );
          },
        ),
      ),
    );
  }
}
