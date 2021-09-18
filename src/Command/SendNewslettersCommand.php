<?php

namespace App\Command;

use App\Entity\Newsletter;
use App\Newsletter\Mailer\NewsletterMailer;
use App\Repository\NewsletterRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendNewslettersCommand extends Command
{
    private SymfonyStyle $io;
    private NewsletterRepository $newsletterRepository;
    private NewsletterMailer $mailer;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('newsletter:send-newsletter')
            ->addArgument(
                'newsletter',
                InputArgument::OPTIONAL,
                'Indicate which newsletter to send. Ex "newsletter:send-newsletter uuid"'
            )
            ->setDescription('Send a newsletter to its subscribers')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    public function __construct(
        NewsletterRepository $newsletterRepository,
        NewsletterMailer $mailer
    )
    {
        parent::__construct();
        $this->newsletterRepository = $newsletterRepository;
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Send a newsletter to its subscribers');
        try {
            $newsletter = $this->getNewsletter($input->getArgument('newsletter'));
            $this->sendNewsletter($newsletter);
            $this->io->success("Newsletter {$newsletter->getTitle()} successfully sent to {$newsletter->getSubscriptions()->count()} subscribers");
        } catch (EntityNotFoundException $exception) {
            $this->io->error($exception->getMessage());
        } catch (TransportExceptionInterface $exception) {
            $this->io->error("Mailer error: {$exception->getMessage()}");
        } catch (\Throwable $throwable) {
            $this->io->error("An unexpected error occurred: {$throwable->getMessage()}");
        }

        return 0;
    }

    /**
     * @param Newsletter[] $newsletters
     */
    private function getRows(array $newsletters, bool $fullRow = true): array
    {
        $rows = [];
        foreach ($newsletters as $newsletter) {
            $rows[] = $fullRow ? [$newsletter->getTitle(), $newsletter->getUuid(), $newsletter->getSubscriptions()->count()] : $newsletter->getUuid();
        }

        return $rows;
    }

    /**
     * @throws EntityNotFoundException
     */
    private function getNewsletter(?string $uuid): Newsletter
    {
        if (null === $uuid) {
            $newsletters = $this->newsletterRepository->findAll();
            $this->io->text('Available newsletters:');
            $this->io->table(
                ['Title', 'Uuid', 'Subscribers'],
                $this->getRows($newsletters)
            );
            $uuid = $this->io->choice('Select the newsletter to send', $this->getRows($newsletters, false));
            $newsletter = $this->newsletterRepository->findOneBy(["uuid" => $uuid]);
        } else {
            $newsletter = $this->newsletterRepository->findOneBy(["uuid" => $uuid]);
            if (!$newsletter) {
                throw new EntityNotFoundException("No newsletter found with uuid: $uuid");
            }
        }

        return $newsletter;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendNewsletter(Newsletter $newsletter): void
    {
        $this->io->text("--> Sending newsletter {$newsletter->getTitle()}...");

        foreach ($newsletter->getSubscriptions() as $subscription) {
            $this->mailer->sendNewsletter($subscription->getUser(), $newsletter);
        }
    }
}