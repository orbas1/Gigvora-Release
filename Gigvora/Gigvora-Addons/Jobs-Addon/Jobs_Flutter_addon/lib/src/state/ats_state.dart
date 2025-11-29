import 'package:flutter/foundation.dart';

import '../models/ats.dart';
import '../models/candidate.dart';
import '../services/employer_service.dart';

class AtsState extends ChangeNotifier {
  AtsState(this.service);

  final EmployerService service;
  Map<String, List<CandidateProfile>> pipeline = {};
  bool loading = false;

  Future<void> loadPipeline(int jobId) async {
    loading = true;
    notifyListeners();
    pipeline = await service.fetchPipeline(jobId);
    loading = false;
    notifyListeners();
  }

  Future<void> moveCandidate(int candidateId, AtsStage stage) async {
    await service.updateStage(candidateId, stage);
  }
}
