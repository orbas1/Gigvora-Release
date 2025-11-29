import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/gig_management.dart';
import '../repositories/freelance_repository.dart';

final gigManagementProvider = FutureProvider.autoDispose.family<GigManagement, int>((ref, id) {
  final repository = ref.watch(freelanceRepositoryProvider);
  return repository.fetchGigManagement(id);
});
