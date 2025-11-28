import 'package:flutter/material.dart';

import '../models/models.dart';

class CreativeListScreen extends StatelessWidget {
  const CreativeListScreen({super.key, this.campaign});

  static const routeName = '/ads/creatives';

  final Campaign? campaign;

  @override
  Widget build(BuildContext context) {
    final creatives = campaign?.adGroups.expand((g) => g.creatives).toList() ?? [];
    return Scaffold(
      appBar: AppBar(title: const Text('Creatives')),
      floatingActionButton: FloatingActionButton(
        onPressed: () => Navigator.of(context).pushNamed('/ads/creatives/edit', arguments: campaign),
        child: const Icon(Icons.add),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: creatives.length,
        itemBuilder: (context, index) {
          final creative = creatives[index];
          return Card(
            child: ListTile(
              leading: CircleAvatar(child: Text(creative.type.substring(0, 1).toUpperCase())),
              title: Text(creative.headline),
              subtitle: Text(creative.description),
              trailing: Chip(label: Text(creative.status)),
              onTap: () => Navigator.of(context).pushNamed('/ads/creatives/edit', arguments: creative),
            ),
          );
        },
      ),
    );
  }
}
