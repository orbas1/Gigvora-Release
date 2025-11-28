import 'package:flutter/material.dart';

import '../../../models/project.dart';
import '../../../ui/screens/projects/project_detail_page.dart';
import 'proposal_edit_screen.dart';

class ProjectDetailScreen extends StatelessWidget {
  const ProjectDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final args = ModalRoute.of(context)?.settings.arguments;
    final project = args is Project ? args : null;
    final slug = project?.slug ?? project?.id.toString();

    return ProjectDetailPage(
      slug: slug ?? '',
      onPropose: () => Navigator.push(
        context,
        MaterialPageRoute(
          builder: (_) => ProposalEditScreen(project: project),
        ),
      ),
    );
  }
}
