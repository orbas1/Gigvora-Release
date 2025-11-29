import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../state/project_management_provider.dart';
import '../../widgets/section_card.dart';

class ProjectManagementPage extends ConsumerWidget {
  const ProjectManagementPage({super.key, required this.projectSlug});

  static const routeName = '/freelance/project/management';

  final String projectSlug;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    if (projectSlug.isEmpty) {
      return Scaffold(
        appBar: AppBar(title: const Text('Project management')),
        body: const Center(
          child: Padding(
            padding: EdgeInsets.all(16),
            child: Text('Pass a projectSlug when pushing this page to load tasks, milestones, and submissions.'),
          ),
        ),
      );
    }

    final board = ref.watch(projectBoardProvider(projectSlug));
    return Scaffold(
      appBar: AppBar(title: const Text('Project management')),
      body: board.when(
        data: (data) => ListView(
          padding: const EdgeInsets.all(16),
          children: [
            Text(data.title, style: Theme.of(context).textTheme.headlineSmall),
            const SizedBox(height: 12),
            if (data.freelancers.isNotEmpty)
              SectionCard(
                title: 'Freelancers',
                child: Wrap(
                  spacing: 8,
                  children: data.freelancers
                      .map((name) => Chip(label: Text(name)))
                      .toList(),
                ),
              ),
            SectionCard(
              title: 'Tasks',
              child: Column(
                children: data.tasks
                    .map(
                      (task) => ListTile(
                        leading: const Icon(Icons.checklist_rtl_outlined),
                        title: Text(task.title),
                        subtitle: Text('${task.status} • ${task.assignee ?? 'Unassigned'}'),
                        trailing: Text('${task.hoursLogged.toStringAsFixed(1)}h'),
                      ),
                    )
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Milestones',
              child: Column(
                children: data.milestones
                    .map(
                      (m) => ListTile(
                        leading: const Icon(Icons.flag_outlined),
                        title: Text(m.title),
                        subtitle: Text('Due: ${m.dueDate?.toLocal().toString().split(' ').first ?? 'n/a'}'),
                        trailing: Text('${m.amount.toStringAsFixed(2)}'),
                      ),
                    )
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Invitations',
              child: Column(
                children: data.invitations
                    .map((inv) => ListTile(
                          leading: const Icon(Icons.send_outlined),
                          title: Text(inv.freelancer),
                          subtitle: Text(inv.message ?? ''),
                          trailing: Text(inv.status),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Submissions',
              child: Column(
                children: data.submissions
                    .map((sub) => ListTile(
                          leading: const Icon(Icons.cloud_upload_outlined),
                          title: Text(sub.status),
                          subtitle: Text(sub.notes),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Hourly logs',
              child: Column(
                children: data.timeLogs
                    .map((log) => ListTile(
                          leading: const Icon(Icons.timer_outlined),
                          title: Text('${log.freelancer} • ${log.hours}h'),
                          subtitle: Text(log.note ?? ''),
                          trailing: Text(log.loggedAt?.toLocal().toString().split(' ').first ?? ''),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Reviews',
              child: Column(
                children: data.reviews
                    .map((rev) => ListTile(
                          leading: const Icon(Icons.reviews_outlined),
                          title: Text('${rev.rating} / 5'),
                          subtitle: Text(rev.comment ?? ''),
                          trailing: Text(rev.author),
                        ))
                    .toList(),
              ),
            ),
          ],
        ),
        error: (error, _) => Center(child: Text('Could not load board: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}
