import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../services/api_client.dart';
import '../../services/employer_service.dart';
import '../../state/employer_state.dart';

class EmployerJobsListScreen extends StatelessWidget {
  const EmployerJobsListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => EmployerState(
        EmployerService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      )..loadJobs(),
      child: Scaffold(
        appBar: AppBar(title: const Text('My Jobs')),
        floatingActionButton: FloatingActionButton(
          onPressed: () => Navigator.pushNamed(context, '/employer/jobs/create'),
          child: const Icon(Icons.add),
        ),
        body: Consumer<EmployerState>(
          builder: (context, state, _) => Column(
            children: [
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                child: Row(
                  children: ['draft', 'open', 'paused', 'closed']
                      .map(
                        (status) => Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 12),
                          child: FilterChip(
                            label: Text(status.toUpperCase()),
                            selected: false,
                            onSelected: (_) => state.loadJobs(status: status),
                          ),
                        ),
                      )
                      .toList(),
                ),
              ),
              Expanded(
                child: ListView.builder(
                  itemCount: state.jobs.length,
                  itemBuilder: (context, index) {
                    final job = state.jobs[index];
                    return ListTile(
                      title: Text(job.title),
                      subtitle: Text('Status: ${job.status ?? 'open'}'),
                      trailing: TextButton(
                        onPressed: () => Navigator.pushNamed(context, '/employer/ats/${job.id}'),
                        child: const Text('ATS'),
                      ),
                    );
                  },
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
