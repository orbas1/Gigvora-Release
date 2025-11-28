import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../models/application.dart';
import '../../services/api_client.dart';
import '../../services/applications_service.dart';
import '../../state/applications_state.dart';
import '../../widgets/application_timeline.dart';

class ApplicationDetailScreen extends StatelessWidget {
  const ApplicationDetailScreen({super.key, required this.applicationId});

  final int applicationId;

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => ApplicationsState(
        ApplicationsService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      )..selectApplication(applicationId),
      child: Scaffold(
        appBar: AppBar(title: const Text('Application Detail')),
        body: Consumer<ApplicationsState>(
          builder: (context, state, _) {
            final app = state.selected;
            if (app == null) {
              return const Center(child: CircularProgressIndicator());
            }
            final events = <TimelineEvent>[
              TimelineEvent(label: 'Submitted', subtitle: 'Your application was sent'),
              if (app.status.code == 'interview')
                TimelineEvent(label: 'Interview', subtitle: 'Interview scheduled'),
              TimelineEvent(label: app.status.label, subtitle: 'Current status'),
            ];
            return Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(app.job.title, style: Theme.of(context).textTheme.titleLarge),
                  Text(app.job.company.name),
                  const SizedBox(height: 16),
                  ApplicationTimeline(events: events),
                  const SizedBox(height: 16),
                  Text('Interview invitations', style: Theme.of(context).textTheme.titleMedium),
                  ...app.job.screeningQuestions
                      .map((q) => ListTile(title: Text(q.prompt), subtitle: const Text('Answer submitted'))),
                  const Spacer(),
                  ElevatedButton(
                    onPressed: () => state.withdraw(applicationId),
                    child: const Text('Withdraw application'),
                  )
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}
