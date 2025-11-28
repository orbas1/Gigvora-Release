import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../models/cv.dart';
import '../../services/api_client.dart';
import '../../services/cv_service.dart';
import '../../state/cv_state.dart';

class CvListScreen extends StatelessWidget {
  const CvListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => CvState(CvService(JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders())))..load(),
      child: Scaffold(
        appBar: AppBar(title: const Text('My CVs')),
        floatingActionButton: FloatingActionButton(
          onPressed: () => Navigator.pushNamed(context, '/jobs/cv/edit'),
          child: const Icon(Icons.add),
        ),
        body: Consumer<CvState>(
          builder: (context, state, _) {
            if (state.loading) {
              return const Center(child: CircularProgressIndicator());
            }
            return ListView.builder(
              itemCount: state.cvs.length,
              itemBuilder: (context, index) {
                final cv = state.cvs[index];
                return ListTile(
                  title: Text(cv.name),
                  subtitle: Text(cv.summary),
                  trailing: IconButton(
                    icon: const Icon(Icons.delete_outline),
                    onPressed: () => state.remove(cv.id),
                  ),
                  onTap: () => Navigator.pushNamed(context, '/jobs/cv/edit', arguments: cv),
                );
              },
            );
          },
        ),
      ),
    );
  }
}
