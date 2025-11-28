import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/tag.dart';
import '../repositories/freelance_repository.dart';
import 'core_providers.dart';

final tagsProvider = FutureProvider.autoDispose.family<List<FreelanceTag>, String?>((ref, type) {
  final repository = ref.watch(freelanceRepositoryProvider);
  return repository.fetchTags(type: type);
});

class TagActions {
  TagActions(this._repository);

  final FreelanceRepository _repository;

  Future<void> updateProfileTags({required List<String> tags, String type = 'freelancer'}) {
    return _repository.updateProfileTags(tags: tags, type: type);
  }

  Future<void> updateGigTags({required int gigId, required List<String> tags}) {
    return _repository.updateGigTags(gigId: gigId, tags: tags);
  }
}

final tagActionsProvider = Provider<TagActions>((ref) {
  final repository = ref.watch(freelanceRepositoryProvider);
  return TagActions(repository);
});
