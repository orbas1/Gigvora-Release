import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../models/ats.dart';
import '../../models/candidate.dart';
import '../../services/api_client.dart';
import '../../services/employer_service.dart';
import '../../state/ats_state.dart';
import '../../widgets/candidate_card.dart';

class AtsBoardScreen extends StatelessWidget {
  const AtsBoardScreen({super.key, required this.jobId});

  final int jobId;

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => AtsState(
        EmployerService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      )..loadPipeline(jobId),
      child: Scaffold(
        appBar: AppBar(title: const Text('ATS Board')),
        body: Consumer<AtsState>(
          builder: (context, state, _) {
            final stages = ['applied', 'screening', 'shortlisted', 'interview', 'offer', 'hired', 'rejected'];
            return SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.all(12),
              child: Row(
                children: stages
                    .map(
                      (stage) => SizedBox(
                        width: 260,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(stage.toUpperCase(), style: Theme.of(context).textTheme.labelLarge),
                            const SizedBox(height: 8),
                            ...state.pipeline[stage]?.map(
                                  (candidate) => CandidateCard(
                                    candidate: candidate,
                                    onTap: () => Navigator.pushNamed(context, '/employer/candidates/${candidate.id}'),
                                    onMove: () => state.moveCandidate(
                                      candidate.id,
                                      AtsStage(code: stage, label: stage),
                                    ),
                                  ),
                                ) ??
                                [const Text('No candidates')],
                          ],
                        ),
                      ),
                    )
                    .toList(),
              ),
            );
          },
        ),
      ),
    );
  }
}
