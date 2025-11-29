import 'package:flutter/material.dart';
import '../../../models/project.dart';
import '../../../widgets/project_card.dart';

class ClientProjectsScreen extends StatelessWidget {
  const ClientProjectsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final projects = [
      Project(id: 1, title: 'Marketing site', description: '', budget: 1200, type: 'fixed', proposalsCount: 6),
    ];
    return Scaffold(
      appBar: AppBar(title: const Text('My Projects'), actions: [
        IconButton(onPressed: () {}, icon: const Icon(Icons.add_circle_outline))
      ]),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Wrap(spacing: 8, children: const [Chip(label: Text('All')), Chip(label: Text('Open')), Chip(label: Text('Completed'))]),
            const SizedBox(height: 12),
            Expanded(
              child: ListView.builder(
                itemCount: projects.length,
                itemBuilder: (context, index) => ProjectCard(
                  project: projects[index],
                  onTap: () => Navigator.pushNamed(context, '/freelance/client/project', arguments: projects[index]),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
