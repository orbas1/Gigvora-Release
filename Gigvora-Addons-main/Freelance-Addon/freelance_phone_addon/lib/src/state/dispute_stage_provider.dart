import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/dispute_stage.dart';
import '../repositories/freelance_repository.dart';

final disputeStagesProvider = FutureProvider.autoDispose.family<List<DisputeStage>, int>((ref, disputeId) {
  final repository = ref.watch(freelanceRepositoryProvider);
  return repository.fetchDisputeStages(disputeId);
});
