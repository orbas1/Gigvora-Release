import 'package:flutter/material.dart';

import '../../models/project.dart';

class ProjectCard extends StatelessWidget {
  const ProjectCard({
    super.key,
    required this.project,
    required this.onTap,
    required this.onFavourite,
  });

  final Project project;
  final VoidCallback onTap;
  final VoidCallback onFavourite;

  @override
  Widget build(BuildContext context) {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: InkWell(
        borderRadius: BorderRadius.circular(12),
        onTap: onTap,
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Text(project.postedAt ?? 'Recently', style: Theme.of(context).textTheme.bodySmall),
                  const Spacer(),
                  IconButton(
                    icon: Icon(project.isFavourite ? Icons.favorite : Icons.favorite_border),
                    onPressed: onFavourite,
                  ),
                ],
              ),
              Text(project.title, style: Theme.of(context).textTheme.titleMedium),
              const SizedBox(height: 8),
              Text(
                project.description.isEmpty
                    ? 'Project details will be loaded from the Laravel freelance package.'
                    : project.description,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: Theme.of(context).textTheme.bodySmall,
              ),
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                runSpacing: 4,
                children: [
                  _pill(context, Icons.location_on_outlined, project.location ?? 'Remote'),
                  _pill(
                    context,
                    Icons.price_change_outlined,
                    '\$${project.budgetMin.toStringAsFixed(0)} - \$${project.budgetMax.toStringAsFixed(0)}',
                  ),
                  _pill(context, Icons.group_outlined, '${project.offers ?? 0} offers'),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _pill(BuildContext context, IconData icon, String text) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: Theme.of(context).colorScheme.primary.withOpacity(0.06),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 14, color: Theme.of(context).colorScheme.primary),
          const SizedBox(width: 4),
          Text(text, style: Theme.of(context).textTheme.bodySmall),
        ],
      ),
    );
  }
}
