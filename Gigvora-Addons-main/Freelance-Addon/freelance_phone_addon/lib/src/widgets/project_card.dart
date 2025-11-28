import 'package:flutter/material.dart';
import '../models/project.dart';

class ProjectCard extends StatelessWidget {
  const ProjectCard({super.key, required this.project, this.onTap});
  final Project project;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        title: Text(project.title),
        subtitle: Text('${project.type.toUpperCase()} · ${project.proposalsCount} proposals'),
        trailing: Text('\$${project.budget.toStringAsFixed(0)}'),
        onTap: onTap,
      ),
    );
  }
}
