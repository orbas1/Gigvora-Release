<?php

namespace App\Extensions\Chatbot\System\Http\Controllers;

use App\Domains\Entity\Enums\EntityEnum;
use App\Domains\Entity\Facades\Entity;
use App\Extensions\Chatbot\System\Http\Requests\ChatbotCustomizeRequest;
use App\Extensions\Chatbot\System\Http\Requests\ChatbotStoreRequest;
use App\Extensions\Chatbot\System\Http\Resources\Admin\ChatbotConversationResource;
use App\Extensions\Chatbot\System\Http\Resources\Admin\ChatbotResource;
use App\Extensions\Chatbot\System\Models\ChatbotConversation;
use App\Extensions\Chatbot\System\Services\ChatbotService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotController extends Controller
{
    public function __construct(public ChatbotService $service) {}

    public function index(Request $request): View
    {
        if (method_exists(Helper::class, 'appIsDemoForChatbot')) {
            if (Helper::appIsDemoForChatbot()) {
                // Clear chatbot for demo mode
                Artisan::call('app:clear-chatbot-demo-mode');
            }
        }

        $externalChatbots = $request->user()->externalChatbots->pluck('id')->toArray();
        $unreadAgentMessagesCount = $this->service->unreadAgentMessagesCount($externalChatbots);
        $unreadAiBotMessagesCount = $this->service->unreadAiBotMessagesCount($externalChatbots);
        $allMessagesCount = $this->service->allMessagesCount($externalChatbots);

        return view('chatbot::index', [
            'chatbots' => $this->service->query()
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')->paginate(perPage: 10),
            'avatars'                  => $this->service->avatars(),
            'unreadAgentMessagesCount' => $unreadAgentMessagesCount,
            'unreadAiBotMessagesCount' => $unreadAiBotMessagesCount,
            'allMessagesCount'         => $allMessagesCount,
        ]);
    }

    public function store(ChatbotStoreRequest $request): JsonResponse|ChatbotResource
    {
        $chatbot = $this->service->query()->create($request->validated());

        return ChatbotResource::make($chatbot);
    }

    public function update(ChatbotCustomizeRequest $request): JsonResponse|ChatbotResource
    {
        $data = $request->validated();

        $chatbot = $this->service->query()->findOrFail($data['id']);

        if ($chatbot->getAttribute('is_demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $chatbot = $this->service->update($data['id'], $data);

        return ChatbotResource::make($chatbot);
    }

    public function conversations(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $chatbots = $request->user()->externalChatbots->pluck('id')->toArray();

        $conversations = $this->service->conversations($chatbots);

        return ChatbotConversationResource::collection($conversations);
    }

    public function conversationsWithPaginate(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $chatbots = $request->user()->externalChatbots->pluck('id')->toArray();

        $conversations = $this->service->conversationsWithPaginate($chatbots);

        return ChatbotConversationResource::collection($conversations);
    }

    public function searchConversations(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $request->validate(['search' => 'required|string']);
        $chatbots = $request->user()->externalChatbots->pluck('id')->toArray();

        $conversations = $this->service->conversationsWithPaginate($chatbots);

        return ChatbotConversationResource::collection($conversations);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required']);

        $chatbot = $this->service->query()->findOrFail($request->get('id'));

        if ($chatbot->getAttribute('is_demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        if ($chatbot->getAttribute('user_id') === Auth::id()) {
            $chatbot->delete();
        } else {
            abort(403);
        }

        return response()->json([
            'message' => 'Chatbot deleted successfully',
            'type'    => 'success',
            'status'  => 200,
        ]);
    }

    public function renameConversation(Request $request, ChatbotConversation $conversation): JsonResponse
    {
        $request->validate(['title' => 'required|string|max:190']);
        $conversation = $this->conversationForUser($conversation->getAttribute('id'));
        $conversation->conversation_name = $request->title;
        $conversation->save();

        Log::info('chatbot.conversation.renamed', [
            'conversation_id' => $conversation->getAttribute('id'),
            'user_id'         => Auth::id(),
        ]);

        return response()->json(['message' => 'Conversation renamed.']);
    }

    public function hideConversation(Request $request, ChatbotConversation $conversation): JsonResponse
    {
        $conversation = $this->conversationForUser($conversation->getAttribute('id'));
        $conversation->is_showed_on_history = false;
        $conversation->save();

        Log::info('chatbot.conversation.hidden', [
            'conversation_id' => $conversation->getAttribute('id'),
            'user_id'         => Auth::id(),
        ]);

        return response()->json(['message' => 'Conversation removed from history.']);
    }

    public function summarizeConversation(Request $request, ChatbotConversation $conversation): JsonResponse
    {
        $conversation = $this->conversationForUser($conversation->getAttribute('id'));
        $histories = $conversation->histories()
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->pluck('message')
            ->filter()
            ->values();

        if ($histories->isEmpty()) {
            return response()->json(['message' => 'Conversation has no messages to summarize.'], 422);
        }

        $settings = Setting::getCache();
        $model = $settings->openai_default_model;
        if (! $model) {
            return response()->json(['message' => 'No AI model configured for summaries.'], 422);
        }
        $driver = Entity::driver(EntityEnum::tryFrom($model));
        $driver?->redirectIfNoCreditBalance();

        try {
            $prompt = 'Provide a concise summary of this conversation highlighting intents, concerns, and next steps: ' . $histories->implode("\n");
            $completion = OpenAI::chat()->create([
                'model'    => $model,
                'messages' => [
                    [
                        'role'    => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            $response = $completion['choices'][0]['message']['content'] ?? '';
            $driver?->input($prompt)->calculateCredit()->decreaseCredit();

            return response()->json(['summary' => $response]);
        } catch (\Throwable $e) {
            Log::error('chatbot.conversation.summarize', [
                'conversation_id' => $conversation->getAttribute('id'),
                'error'           => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Unable to summarize conversation at this time.'], 500);
        }
    }

    private function conversationForUser(int $conversationId): ChatbotConversation
    {
        $conversation = ChatbotConversation::with('chatbot')->findOrFail($conversationId);

        abort_if($conversation->chatbot?->user_id !== Auth::id(), 403);

        return $conversation;
    }
}
