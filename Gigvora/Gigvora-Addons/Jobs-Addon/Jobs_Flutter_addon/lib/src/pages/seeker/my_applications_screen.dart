import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../services/api_client.dart';
import '../../services/applications_service.dart';
import '../../state/applications_state.dart';

class MyApplicationsScreen extends StatelessWidget {
  const MyApplicationsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => ApplicationsState(
        ApplicationsService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      )..loadApplications(),
      child: DefaultTabController(
        length: 5,
        child: Scaffold(
          appBar: AppBar(
            title: const Text('My Applications'),
            bottom: const TabBar(
              isScrollable: true,
              tabs: [
                Tab(text: 'All'),
                Tab(text: 'In progress'),
                Tab(text: 'Interview'),
                Tab(text: 'Rejected'),
                Tab(text: 'Hired'),
              ],
            ),
          ),
          body: Consumer<ApplicationsState>(
            builder: (context, state, _) {
              if (state.loading) {
                return const Center(child: CircularProgressIndicator());
              }
              final tabs = [
                state.applications,
                state.applications.where((a) => a.status.code == 'screening').toList(),
                state.applications.where((a) => a.status.code == 'interview').toList(),
                state.applications.where((a) => a.status.code == 'rejected').toList(),
                state.applications.where((a) => a.status.code == 'hired').toList(),
              ];
              return TabBarView(
                children: tabs
                    .map(
                      (list) => ListView.builder(
                        itemCount: list.length,
                        itemBuilder: (context, index) {
                          final app = list[index];
                          return ListTile(
                            title: Text(app.job.title),
                            subtitle: Text(app.job.company.name),
                            trailing: Text(app.status.label),
                            onTap: () => Navigator.pushNamed(context, '/jobs/applications/${app.id}'),
                          );
                        },
                      ),
                    )
                    .toList(),
              );
            },
          ),
        ),
      ),
    );
  }
}
