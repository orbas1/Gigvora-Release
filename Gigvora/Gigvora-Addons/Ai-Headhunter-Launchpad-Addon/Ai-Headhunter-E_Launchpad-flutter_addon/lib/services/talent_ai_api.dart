import '../models/talent_ai_models.dart';
import 'api_client.dart';

class HeadhunterApi extends BaseApiService {
  HeadhunterApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<HeadhunterProfileModel> profile() async {
    final data = await get('/api/addons/talent-ai/headhunters/profile')
        as Map<String, dynamic>;
    return HeadhunterProfileModel.fromJson(
        (data['profile'] as Map<String, dynamic>?) ?? data);
  }

  Future<PaginatedResult<HeadhunterMandateModel>> mandates({
    int page = 1,
    Map<String, dynamic>? filters,
  }) async {
    final query = <String, dynamic>{'page': page, ...?filters};
    final data = await getWithQuery(
      '/api/addons/talent-ai/headhunters/mandates',
      query,
    ) as Map<String, dynamic>;
    return paginatedFromJson(data, (json) => HeadhunterMandateModel.fromJson(json));
  }

  Future<HeadhunterMandateModel> mandate(int id) async {
    final data = await get('/api/addons/talent-ai/headhunters/mandates/$id')
        as Map<String, dynamic>;
    return HeadhunterMandateModel.fromJson(
        (data['mandate'] as Map<String, dynamic>?) ?? data);
  }

  Future<List<HeadhunterPipelineItemModel>> pipeline(int mandateId) async {
    final data = await get(
            '/api/addons/talent-ai/headhunters/mandates/$mandateId/pipeline')
        as Map<String, dynamic>;
    final items = (data['items'] as List?) ?? data['data'] as List? ?? [];
    return items
        .map((e) => HeadhunterPipelineItemModel.fromJson(
            e as Map<String, dynamic>))
        .toList();
  }

  Future<HeadhunterPipelineItemModel> movePipelineItem({
    required int mandateId,
    required int itemId,
    required String stage,
  }) async {
    final data = await post(
      '/api/addons/talent-ai/headhunters/mandates/$mandateId/pipeline/$itemId',
      data: {'stage': stage},
    ) as Map<String, dynamic>;
    return HeadhunterPipelineItemModel.fromJson(
        (data['item'] as Map<String, dynamic>?) ?? data);
  }

  Future<HeadhunterCandidateModel> candidate(int id) async {
    final data = await get('/api/addons/talent-ai/headhunters/candidates/$id')
        as Map<String, dynamic>;
    return HeadhunterCandidateModel.fromJson(
        (data['candidate'] as Map<String, dynamic>?) ?? data);
  }

  Future<void> updateCandidateNotes(int id, String notes) async {
    await post('/api/addons/talent-ai/headhunters/candidates/$id/notes',
        data: {'notes': notes});
  }

  Future<List<HeadhunterInterviewModel>> interviews(int candidateId) async {
    final data = await get(
        '/api/addons/talent-ai/headhunters/candidates/$candidateId/interviews');
    final list = (data as Map<String, dynamic>)['interviews'] as List? ?? [];
    return list
        .map((e) => HeadhunterInterviewModel.fromJson(
            e as Map<String, dynamic>))
        .toList();
  }
}

class LaunchpadApi extends BaseApiService {
  LaunchpadApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<PaginatedResult<LaunchpadProgrammeModel>> programmes({
    int page = 1,
    Map<String, dynamic>? filters,
  }) async {
    final query = <String, dynamic>{'page': page, ...?filters};
    final data = await getWithQuery(
      '/api/addons/talent-ai/launchpad/programmes',
      query,
    ) as Map<String, dynamic>;
    return paginatedFromJson(data, (json) => LaunchpadProgrammeModel.fromJson(json));
  }

  Future<LaunchpadProgrammeModel> programme(int id) async {
    final data = await get('/api/addons/talent-ai/launchpad/programmes/$id')
        as Map<String, dynamic>;
    return LaunchpadProgrammeModel.fromJson(
        (data['programme'] as Map<String, dynamic>?) ?? data);
  }

  Future<List<LaunchpadTaskModel>> tasks(int programmeId) async {
    final data = await get(
        '/api/addons/talent-ai/launchpad/programmes/$programmeId/tasks');
    final list = (data as Map<String, dynamic>)['tasks'] as List? ?? [];
    return list
        .map((e) => LaunchpadTaskModel.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<LaunchpadApplicationModel> apply(int programmeId,
      {Map<String, dynamic>? payload}) async {
    final data = await post(
      '/api/addons/talent-ai/launchpad/programmes/$programmeId/applications',
      data: payload ?? {},
    ) as Map<String, dynamic>;
    return LaunchpadApplicationModel.fromJson(
        (data['application'] as Map<String, dynamic>?) ?? data);
  }

  Future<LaunchpadApplicationModel> application(int id) async {
    final data = await get('/api/addons/talent-ai/launchpad/applications/$id')
        as Map<String, dynamic>;
    return LaunchpadApplicationModel.fromJson(
        (data['application'] as Map<String, dynamic>?) ?? data);
  }

  Future<LaunchpadApplicationModel> updateTask(
    int applicationId,
    int taskId,
    bool completed,
  ) async {
    final data = await post(
      '/api/addons/talent-ai/launchpad/applications/$applicationId/tasks/$taskId',
      data: {'completed': completed},
    ) as Map<String, dynamic>;
    return LaunchpadApplicationModel.fromJson(
        (data['application'] as Map<String, dynamic>?) ?? data);
  }
}

class AiWorkspaceApi extends BaseApiService {
  AiWorkspaceApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<AiSessionModel> runTool(String tool, Map<String, dynamic> payload) async {
    final data = await post('/api/addons/talent-ai/ai-workspace/tools/$tool',
        data: payload) as Map<String, dynamic>;
    return AiSessionModel.fromJson((data['session'] as Map<String, dynamic>?) ?? data);
  }

  Future<List<AiSessionModel>> sessions() async {
    final data = await get('/api/addons/talent-ai/ai-workspace/sessions')
        as Map<String, dynamic>;
    final list = (data['sessions'] as List?) ?? data['data'] as List? ?? [];
    return list
        .map((e) => AiSessionModel.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<AiUsageAggregateModel> usage() async {
    final data = await get('/api/addons/talent-ai/ai-workspace/usage')
        as Map<String, dynamic>;
    return AiUsageAggregateModel.fromJson(
        (data['usage'] as Map<String, dynamic>?) ?? data);
  }

  Future<List<AiSubscriptionPlanModel>> plans() async {
    final data = await get('/api/addons/talent-ai/ai-workspace/plans')
        as Map<String, dynamic>;
    final list = (data['plans'] as List?) ?? data['data'] as List? ?? [];
    return list
        .map((e) => AiSubscriptionPlanModel.fromJson(
            e as Map<String, dynamic>))
        .toList();
  }

  Future<AiUserSubscriptionModel> subscription() async {
    final data = await get('/api/addons/talent-ai/ai-workspace/subscription')
        as Map<String, dynamic>;
    return AiUserSubscriptionModel.fromJson(
        (data['subscription'] as Map<String, dynamic>?) ?? data);
  }
}

class VolunteeringApi extends BaseApiService {
  VolunteeringApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<PaginatedResult<VolunteeringOpportunityModel>> opportunities({
    int page = 1,
    Map<String, dynamic>? filters,
  }) async {
    final query = <String, dynamic>{'page': page, ...?filters};
    final data = await getWithQuery(
      '/api/addons/talent-ai/volunteering/opportunities',
      query,
    ) as Map<String, dynamic>;
    return paginatedFromJson(
        data, (json) => VolunteeringOpportunityModel.fromJson(json));
  }

  Future<VolunteeringOpportunityModel> opportunity(int id) async {
    final data = await get('/api/addons/talent-ai/volunteering/opportunities/$id')
        as Map<String, dynamic>;
    return VolunteeringOpportunityModel.fromJson(
        (data['opportunity'] as Map<String, dynamic>?) ?? data);
  }

  Future<VolunteeringApplicationModel> apply(int opportunityId,
      {Map<String, dynamic>? payload}) async {
    final data = await post(
      '/api/addons/talent-ai/volunteering/opportunities/$opportunityId/applications',
      data: payload ?? {},
    ) as Map<String, dynamic>;
    return VolunteeringApplicationModel.fromJson(
        (data['application'] as Map<String, dynamic>?) ?? data);
  }

  Future<List<VolunteeringApplicationModel>> myApplications() async {
    final data = await get('/api/addons/talent-ai/volunteering/applications')
        as Map<String, dynamic>;
    final list = (data['applications'] as List?) ?? data['data'] as List? ?? [];
    return list
        .map((e) => VolunteeringApplicationModel.fromJson(
            e as Map<String, dynamic>))
        .toList();
  }

  Future<VolunteeringApplicationModel> application(int id) async {
    final data = await get('/api/addons/talent-ai/volunteering/applications/$id')
        as Map<String, dynamic>;
    return VolunteeringApplicationModel.fromJson(
        (data['application'] as Map<String, dynamic>?) ?? data);
  }
}
